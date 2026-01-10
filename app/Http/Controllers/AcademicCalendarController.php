<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // 1. Ambil data dari Database
        $dbEvents = AcademicCalendar::with('mataKuliah')->get();
        $mata_kuliahs = MataKuliah::all();

        // 2. Ambil data dari API Hari Libur Nasional
        $apiEvents = [];
        try {
            $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");
            if ($response->successful()) {
                foreach ($response->json() as $holiday) {
                    $holidayDate = Carbon::parse($holiday['tanggal']);
                    // Hanya ambil libur untuk bulan yang sedang ditampilkan
                    if ($holidayDate->month == $month) {
                        $apiEvents[] = (object)[
                            'id' => null,
                            'tanggal' => $holiday['tanggal'],
                            'nama_kegiatan' => $holiday['keterangan'],
                            'tipe' => 'libur',
                            'is_api' => true // Penanda data dari API
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Jika API gagal, aplikasi tetap jalan
        }

        // 3. Gabungkan data DB dan API
        $allEvents = collect($dbEvents)->concat($apiEvents);

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