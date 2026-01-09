<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticumBudget;
use App\Models\MataKuliah; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = PracticumBudget::with('mataKuliah');
        $judulHalaman = "Semua Mata Kuliah"; // Judul Default

        // 2. Cek apakah ada pencarian?
        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $judulHalaman = "Hasil Pencarian: \"" . $keyword . "\"";

            // Filter data berdasarkan Nama Bahan ATAU Nama Mata Kuliah
            $query->where('nama_bahan', 'like', '%' . $keyword . '%')
                  ->orWhereHas('mataKuliah', function($q) use ($keyword) {
                      $q->where('nama_mk', 'like', '%' . $keyword . '%');
                  });
        }

        // 3. Eksekusi Query
        $dataAnggaran = $query->latest()->get();
        
        // 4. Ambil Data Master Matkul untuk Dropdown (Tetap)
        $daftarMatkul = MataKuliah::all();

        // Kirim $judulHalaman ke View
        return view('budget.index', compact('dataAnggaran', 'daftarMatkul', 'judulHalaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_bahan' => 'required',
            'jumlah' => 'required|numeric',
            'estimasi_harga' => 'required|numeric',
        ]);

        // --- LOGIKA API ---
        $hargaPasar = $request->estimasi_harga; 
        $sumberData = 'Estimasi Manual';
        $statusApi = false;
        $kursDollar = 16000; 

        try {
            $response = Http::withoutVerifying()->timeout(5)->get('https://dummyjson.com/products/search', [
                'q' => $request->nama_bahan,
                'limit' => 1 
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['products'])) {
                    $produk = $data['products'][0]; 
                    $hargaPasar = $produk['price'] * $kursDollar;
                    $sumberData = "API Global ({$produk['title']})"; 
                    $statusApi = true;
                }
            }
        } catch (\Exception $e) {}
        // --- END LOGIKA API ---

        // Panggil helper function (Pastikan isinya ada!)
        $status = $this->tentukanStatus($request->estimasi_harga, $hargaPasar);

        PracticumBudget::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_bahan' => $request->nama_bahan,
            'jumlah' => $request->jumlah,
            'estimasi_harga' => $request->estimasi_harga,
            'harga_pasar' => $hargaPasar,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $budget = PracticumBudget::findOrFail($id);
        $hargaPasar = $budget->harga_pasar;
        $kursDollar = 16000;

        // Cek API lagi jika nama bahan berubah
        if ($request->nama_bahan != $budget->nama_bahan) {
            try {
                $response = Http::withoutVerifying()->timeout(5)->get('https://dummyjson.com/products/search', [
                    'q' => $request->nama_bahan,
                    'limit' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['products'])) {
                        $hargaPasar = $data['products'][0]['price'] * $kursDollar;
                    }
                }
            } catch (\Exception $e) {}
        }

        $status = $this->tentukanStatus($request->estimasi_harga, $hargaPasar);

        $budget->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_bahan' => $request->nama_bahan,
            'jumlah' => $request->jumlah,
            'estimasi_harga' => $request->estimasi_harga,
            'harga_pasar' => $hargaPasar,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $budget = PracticumBudget::findOrFail($id);
        $budget->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function cetakPdf()
    {
        $dataAnggaran = PracticumBudget::all();
        $pdf = Pdf::loadView('budget.cetak_pdf', ['dataAnggaran' => $dataAnggaran]);
        return $pdf->download('laporan-anggaran.pdf');
    }

    // INI YANG TADI MENYEBABKAN ERROR JIKA ISINYA KOSONG
    private function tentukanStatus($estimasi, $pasar)
    {
        if ($estimasi < $pasar) {
            return 'Valid'; 
        } elseif ($estimasi <= ($pasar * 1.10)) {
            return 'Aman'; 
        } else {
            return 'Peringatan'; 
        }
    }
}