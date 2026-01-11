<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\SesiKelas;
use App\Models\SesiPraktikum;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // 1. Ambil data Agenda Manual dari Database
        $dbEvents = AcademicCalendar::with('mataKuliah')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        // 2. Ambil data dari Sesi Kelas
        $kelasEvents = SesiKelas::whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get()
            ->map(function($item) {
                return (object)[
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'nama_kegiatan' => "Kelas: " . $item->mata_kuliah, // Menggunakan kolom mata_kuliah di tabel sesi_kelas
                    'tipe' => 'matkul',
                    'is_api' => false
                ];
            });

        // 3. Ambil data dari Sesi Praktikum
        $praktikumEvents = SesiPraktikum::whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get()
            ->map(function($item) {
                return (object)[
                    'id' => $item->id,
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'nama_kegiatan' => "Praktikum: " . $item->mata_kuliah, // Menggunakan kolom mata_kuliah di tabel sesi_praktikums
                    'tipe' => 'matkul',
                    'is_api' => false
                ];
            });

        // 4. Ambil data dari API Hari Libur
        $apiEvents = [];
        try {
            $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");
            if ($response->successful()) {
                foreach ($response->json() as $holiday) {
                    $holidayDate = Carbon::parse($holiday['tanggal']);
                    if ($holidayDate->month == $month) {
                        $apiEvents[] = (object)[
                            'id' => null,
                            'tanggal' => $holiday['tanggal'],
                            'nama_kegiatan' => $holiday['keterangan'],
                            'tipe' => 'libur',
                            'is_api' => true
                        ];
                    }
                }
            }
        } catch (\Exception $e) {}

        // 5. Gabungkan Semua Sumber Data
        $allEvents = collect($dbEvents)
            ->concat($kelasEvents)
            ->concat($praktikumEvents)
            ->concat($apiEvents);

        $mata_kuliahs = MataKuliah::all();

        return view('Academic.index', compact('allEvents', 'month', 'year', 'mata_kuliahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'nullable|string',
            'tipe' => 'required|in:libur,kegiatan,matkul',
            'mata_kuliah_id' => 'nullable|exists:mata_kuliahs,id'
        ]);

        // Jika matkul, otomatis ambil nama MK sebagai nama kegiatan jika kosong
        if ($request->tipe == 'matkul' && $request->mata_kuliah_id) {
            $mk = MataKuliah::find($request->mata_kuliah_id);
            $validated['nama_kegiatan'] = $validated['nama_kegiatan'] ?? $mk->nama_mk;
        }

        AcademicCalendar::create($validated);
        return redirect()->back()->with('success', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $event = AcademicCalendar::findOrFail($id);
        $event->update($request->all());
        return redirect()->back();
    }

    public function destroy($id)
    {
        $event = AcademicCalendar::findOrFail($id);
        $event->delete();
        return redirect()->back();
    }
}