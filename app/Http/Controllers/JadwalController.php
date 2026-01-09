<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
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
        $jadwal = $this->getCombinedJadwal();
        $masterMk = MataKuliah::all();
        return view('dosen.jadwal.index', compact('jadwal', 'masterMk'));

    
    }

    private function getCombinedJadwal()
    {
        // 1. Ambil Data Praktikum & Mapping
        $praktikum = SesiPraktikum::with('mataKuliah')->where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'practicum';
            $item->label_jenis = 'PRAKTIKUM';
            $item->mata_kuliah = $item->mataKuliah->nama_mata_kuliah ?? 'MK Tidak Ditemukan';
            $item->css_badge = 'border-purple-200 bg-purple-50 text-purple-600';
            $item->ruangan = $item->ruangan_lab; 
            return $item;
        });

        // 2. Ambil Data Kuliah & Mapping
        $kelas = SesiKelas::with('mataKuliah')->where('dosen_id', 1)->get()->map(function($item) {
            $item->type = 'lecture';
            $item->label_jenis = 'KULIAH';
            $item->mata_kuliah = $item->mataKuliah->nama_mata_kuliah ?? 'MK Tidak Ditemukan';
            $item->css_badge = 'border-indigo-200 bg-indigo-50 text-indigo-600';
            $item->ruangan = $item->ruangan_kelas;
            return $item;
        });

        return $praktikum->merge($kelas)->sortBy(function($item) {
            return $item->tanggal . $item->waktu_mulai;
        });
    }

    public function exportPdf()
    {
        try {
            $jadwal = $this->getCombinedJadwal();
            $pdf = Pdf::loadView('dosen.jadwal.pdf', [
                'jadwal' => $jadwal,
                'tgl_centak' => Carbon::now()->translatedFormat('d F Y H:i'),
                'nama_dosen' => 'Dr. John Doe',
            ]);
                
        // Atur Kertas A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        // Download file
        return $pdf->download('jadwal_mata_kuliah.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required',
            'ruangan'     => 'required',
            'type'        => 'required',
            'tanggal'     => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ]);

        $validasi = $this->checkPrayerTimeConflict($request->tanggal, $request->waktu_mulai, $request->waktu_selesai);

        $data = [
            'dosen_id' => 1,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'mata_kuliah' => MataKuliah::find($request->mata_kuliah_id)->nama_mk,
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

        return redirect()->back()->with('success', 'Rencana berhasil disimpan. Status: ' . $validasi['pesan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mata_kuliah_id' => 'required',
            'ruangan'     => 'required',
            'tanggal'     => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'original_type' => 'required'
        ]);

        $validasi = $this->checkPrayerTimeConflict($request->tanggal, $request->waktu_mulai, $request->waktu_selesai);

        $dataUpdate = [
            'mata_kuliah_id' => $request->mata_kuliah_id,
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

        return redirect()->back()->with('success', 'Jadwal diperbarui. Status: ' . $validasi['pesan']);
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

    private function checkPrayerTimeConflict($tanggalInput, $mulai, $selesai)
    {
        try {
            $date = Carbon::parse($tanggalInput);
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $tglString = $date->format('Y-m-d');
            
            $cityId = '1219'; 
            $apiUrl = "https://api.myquran.com/v1/sholat/jadwal/$cityId/$year/$month/$day";

            $response = Http::withoutVerifying()->timeout(5)->get($apiUrl);

            if ($response->successful()) {
                $json = $response->json();
                
                if (isset($json['data']['jadwal'])) {
                    $jadwal = $json['data']['jadwal'];

                    $start   = Carbon::parse("$tglString $mulai");
                    $end     = Carbon::parse("$tglString $selesai");

                    $dzuhur  = Carbon::parse("$tglString " . $jadwal['dzuhur']);
                    $ashar   = Carbon::parse("$tglString " . $jadwal['ashar']);
                    $maghrib = Carbon::parse("$tglString " . $jadwal['maghrib']);
                    
                    if ($date->isFriday()) {
                        $mulaiJumat = Carbon::parse("$tglString 11:50");
                        $selesaiJumat = Carbon::parse("$tglString 13:00");

                        if ($start->lt($selesaiJumat) && $end->gt($mulaiJumat)) {
                            return ['status' => 'bentrok', 'pesan' => 'Bentrok Sholat Jumat'];
                        }
                    }

                    if (!$date->isFriday()) {
                        if ($start->lt($dzuhur) && $end->gt($dzuhur)) {
                            return ['status' => 'bentrok', 'pesan' => 'Bentrok Dzuhur (' . $jadwal['dzuhur'] . ')'];
                        }
                    }

                    if ($start->lt($ashar) && $end->gt($ashar)) {
                        return ['status' => 'bentrok', 'pesan' => 'Bentrok Ashar (' . $jadwal['ashar'] . ')'];
                    }

                    if ($start->lt($maghrib) && $end->gt($maghrib)) {
                        return ['status' => 'warning', 'pesan' => 'Bentrok Maghrib (' . $jadwal['maghrib'] . ')'];
                    }

                    return ['status' => 'aman', 'pesan' => 'Aman'];
                }
            }
            return ['status' => 'aman', 'pesan' => 'Data API Kosong (Cek Manual)'];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return ['status' => 'warning', 'pesan' => 'Koneksi API Gagal'];
        } catch (\Exception $e) {
            return ['status' => 'warning', 'pesan' => 'Sys Error: ' . $e->getMessage()];
        }
    }
}