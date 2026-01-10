<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPraktikum;
use App\Models\SesiKelas; 
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    public function index()
    {
        $praktikum = SesiPraktikum::where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'practicum';
            $item->label_jenis = 'PRAKTIKUM';
            $item->css_badge = 'border-purple-200 bg-purple-50 text-purple-600';
            $item->ruangan = $item->ruangan_lab; 
            return $item;
        });

        $kelas = SesiKelas::where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'lecture';
            $item->label_jenis = 'KULIAH';
            $item->css_badge = 'border-indigo-200 bg-indigo-50 text-indigo-600';
            $item->ruangan = $item->ruangan_kelas;
            return $item;
        });

        $jadwal = $praktikum->merge($kelas)->sortBy(function($item) {
            return $item->tanggal . $item->waktu_mulai;
        });

        return view('dosen.jadwal.index', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'after'    => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.',
        ];

        $request->validate([
            'mata_kuliah' => 'required',
            'ruangan'     => 'required',
            'type'        => 'required',
            'tanggal'     => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ], $messages);

        $validasi = $this->checkPrayerTimeConflict($request->tanggal, $request->waktu_mulai, $request->waktu_selesai);

        $data = [
            'dosen_id' => 1,
            'mata_kuliah' => $request->mata_kuliah,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status_validasi_ibadah' => $validasi['status'],
            'keterangan_konflik' => $validasi['pesan'],
        ];

        if ($request->type === 'practicum') {
            $data['ruangan_lab'] = $request->ruangan;
            SesiPraktikum::create($data);
        } else {
            $data['ruangan_kelas'] = $request->ruangan;
            SesiKelas::create($data);
        }

        return redirect()->back()->with('success', 'Rencana berhasil disimpan. ' . $validasi['pesan']);
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'after'    => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.',
        ];

        $request->validate([
            'mata_kuliah' => 'required',
            'ruangan'     => 'required',
            'tanggal'     => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'original_type' => 'required'
        ], $messages);

        $validasi = $this->checkPrayerTimeConflict($request->tanggal, $request->waktu_mulai, $request->waktu_selesai);

        $dataUpdate = [
            'mata_kuliah' => $request->mata_kuliah,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status_validasi_ibadah' => $validasi['status'],
            'keterangan_konflik' => $validasi['pesan'],
        ];

        if ($request->type !== $request->original_type) {
            $this->destroy($request->original_type, $id);
            return $this->store($request);
        }

        if ($request->type === 'practicum') {
            $dataUpdate['ruangan_lab'] = $request->ruangan;
            SesiPraktikum::findOrFail($id)->update($dataUpdate);
        } else {
            $dataUpdate['ruangan_kelas'] = $request->ruangan;
            SesiKelas::findOrFail($id)->update($dataUpdate);
        }

        return redirect()->back()->with('success', 'Jadwal diperbarui. ' . $validasi['pesan']);
    }

    public function destroy($type, $id)
    {
        if ($type === 'practicum') {
            SesiPraktikum::findOrFail($id)->delete();
        } else {
            SesiKelas::findOrFail($id)->delete();
        }
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function exportPdf()
    {
        $praktikum = SesiPraktikum::where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'practicum';
            $item->label_jenis = 'PRAKTIKUM';
            $item->ruangan = $item->ruangan_lab; 
            return $item;
        });

        $kelas = SesiKelas::where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'lecture';
            $item->label_jenis = 'KULIAH';
            $item->ruangan = $item->ruangan_kelas;
            return $item;
        });

        $jadwal = $praktikum->merge($kelas)->sortBy(function($item) {
            return $item->tanggal . $item->waktu_mulai;
        });

        $pdf = Pdf::loadView('dosen.jadwal.pdf', compact('jadwal'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Jadwal_Mengajar.pdf');
    }

    /**
     * FUNGSI UTAMA: CEK TABRAKAN JADWAL DENGAN WAKTU SHOLAT
     * Provider: ALADHAN API (Internasional & Kalkulatif)
     * Kelebihan: Bisa menghitung waktu sholat untuk tahun berapapun (2025, 2030, dll)
     */
    private function checkPrayerTimeConflict($tanggalInput, $mulai, $selesai)
    {
        try {
            $date = Carbon::parse($tanggalInput);
            $dateStr = $date->format('d-m-Y'); // Format API Aladhan: DD-MM-YYYY
            $tglString = $date->format('Y-m-d');
            
            // Koordinat Kota Bandung
            $lat = '-6.9175';
            $long = '107.6191';
            
            // Method 20 = Kemenag RI (jika tersedia) atau Default Muslim World League
            $apiUrl = "http://api.aladhan.com/v1/timings/$dateStr?latitude=$lat&longitude=$long&method=20";

            $response = Http::withoutVerifying()->timeout(8)->get($apiUrl);

            if ($response->successful()) {
                $json = $response->json();
                
                if (isset($json['data']['timings'])) {
                    $timings = $json['data']['timings'];

                    $start   = Carbon::parse("$tglString $mulai");
                    $end     = Carbon::parse("$tglString $selesai");

                    // Ambil waktu sholat dari API
                    $dzuhur  = Carbon::parse("$tglString " . $timings['Dhuhr']);
                    $ashar   = Carbon::parse("$tglString " . $timings['Asr']);
                    $maghrib = Carbon::parse("$tglString " . $timings['Maghrib']);
                    
                    // 1. Cek Sholat Jumat
                    if ($date->isFriday()) {
                        // Jumatan kita set fix: 11:50 - 13:00
                        $mulaiJumat = Carbon::parse("$tglString 11:50");
                        $selesaiJumat = Carbon::parse("$tglString 13:00");

                        // Bentrok jika jadwal kuliah ada di dalam rentang Jumatan
                        if ($start->lt($selesaiJumat) && Carbon::parse("$tglString $selesai")->gt($mulaiJumat)) {
                            return ['status' => 'bentrok', 'pesan' => 'Bentrok Sholat Jumat'];
                        }
                    }

                    // 2. Cek Dzuhur (Non-Jumat)
                    if (!$date->isFriday()) {
                        // Jika kuliah dimulai SEBELUM/PAS Adzan, DAN selesai SETELAH Adzan
                        if ($start->lte($dzuhur) && Carbon::parse("$tglString $selesai")->gt($dzuhur)) {
                            return ['status' => 'bentrok', 'pesan' => 'Bentrok Dzuhur (' . $timings['Dhuhr'] . ')'];
                        }
                    }

                    // 3. Cek Ashar
                    if ($start->lte($ashar) && Carbon::parse("$tglString $selesai")->gt($ashar)) {
                        return ['status' => 'bentrok', 'pesan' => 'Bentrok Ashar (' . $timings['Asr'] . ')'];
                    }

                    // 4. Cek Maghrib
                    if ($start->lte($maghrib) && Carbon::parse("$tglString $selesai")->gt($maghrib)) {
                        return ['status' => 'bentrok', 'pesan' => 'Bentrok Maghrib (' . $timings['Maghrib'] . ')'];
                    }

                    return ['status' => 'aman', 'pesan' => 'Aman'];
                }
            }
            
            // Jika API gagal memberikan data timings
            return ['status' => 'aman', 'pesan' => 'Aman (Data N/A)'];

        } catch (\Exception $e) {
            // Jika error koneksi atau lainnya, default Aman
            return ['status' => 'aman', 'pesan' => 'Aman (Offline)'];
        }
    }
}