<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticumBudget;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class BudgetController extends Controller
{
    public function index()
    {
        $dataAnggaran = PracticumBudget::latest()->get();
        return view('budget.index', compact('dataAnggaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'jumlah' => 'required|numeric',
            'estimasi_harga' => 'required|numeric',
        ]);

        // --- MULAI INTEGRASI ---
        
        $hargaPasar = $request->estimasi_harga; 
        $sumberData = 'Estimasi Manual';
        $statusApi = false;
        $kursDollar = 16000; 
        $debugError = '';

        try {
            $response = Http::withoutVerifying()->timeout(5)->get('https://dummyjson.com/products/search', [
                'q' => $request->nama_bahan,
                'limit' => 1 
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data['products'])) {
                    $produk = $data['products'][0]; 
                    
                    $hargaDollar = $produk['price'];
                    $hargaRupiah = $hargaDollar * $kursDollar;

                    $hargaPasar = $hargaRupiah;
                    $sumberData = "API Global ({$produk['title']})"; 
                    $statusApi = true;
                } else {
                    $debugError = "Koneksi OK, tapi barang '{$request->nama_bahan}' tidak ada di database DummyJSON.";
                }
            } else {
                $debugError = "Server API Menolak. Status: " . $response->status();
            }

        } catch (\Exception $e) {
            $debugError = "Error Koneksi: " . $e->getMessage();
        }


        $status = $this->tentukanStatus($request->estimasi_harga, $hargaPasar);

        PracticumBudget::create([
            'mata_kuliah' => $request->mata_kuliah ?? 'Pengembangan Aplikasi Website',
            'nama_bahan' => $request->nama_bahan,
            'jumlah' => $request->jumlah,
            'estimasi_harga' => $request->estimasi_harga,
            'harga_pasar' => $hargaPasar,
            'status' => $status
        ]);

        if ($statusApi) {
            $pesan = "Sukses! Harga divalidasi via $sumberData.";
        } else {
            $pesan = "GAGAL API: " . $debugError;
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function update(Request $request, $id)
    {
        $budget = PracticumBudget::findOrFail($id);
        $hargaPasar = $budget->harga_pasar;
        $kursDollar = 16000;

        if ($request->nama_bahan != $budget->nama_bahan) {
            try {
                $response = Http::timeout(5)->get('https://dummyjson.com/products/search', [
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

    public function cetakPdf()
    {
        $dataAnggaran = PracticumBudget::all();
    
        $pdf = Pdf::loadView('budget.cetak_pdf', ['dataAnggaran' => $dataAnggaran]);
        
        return $pdf->download('laporan-anggaran.pdf');
    }
}