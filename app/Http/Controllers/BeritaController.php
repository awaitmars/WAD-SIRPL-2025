<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\berita;
use App\Models\MataKuliah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $query = berita::with('mataKuliah')->latest();

        if ($request->has('filter_matkul') && $request->filter_matkul != '') {
            $query->where('mata_kuliah_id', $request->filter_matkul);
        }

        $beritaTersimpan = $query->get();
        $daftarMatkul = MataKuliah::all(); 
        $artikelBerita = [];

        if ($request->has('keyword') && $request->keyword != null) {
            $timeQuery = "";
            if($request->has('waktu')) {
                switch($request->waktu) {
                    case '7d': $timeQuery = " when:7d"; break;
                    case '30d': $timeQuery = " when:30d"; break;
                    default: $timeQuery = " when:1d"; break;
                }
            }

            $queryOriginal = $request->keyword . $timeQuery;
            $keywordEncoded = urlencode($queryOriginal);
            $url = "https://news.google.com/rss/search?q={$keywordEncoded}&hl=id-ID&gl=ID&ceid=ID:id";

            try {
                $responseXML = @file_get_contents($url);
                if ($responseXML) {
                    $xmlObject = simplexml_load_string($responseXML);
                    if ($xmlObject && isset($xmlObject->channel->item)) {
                        $limit = 0;
                        foreach ($xmlObject->channel->item as $item) {
                            if ($limit >= 5) break;
                            $randomImage = 'https://loremflickr.com/320/240/business,technology?random=' . $limit;
                            $artikelBerita[] = [
                                'title' => (string)$item->title,
                                'source' => ['name' => (string)$item->source],
                                'url' => (string)$item->link,
                                'urlToImage' => $randomImage,
                                'publishedAt' => (string)$item->pubDate, 
                            ];
                            $limit++;
                        }
                    }
                }
            } catch (\Exception $e) { }
        }

        return view('kliping.index', compact('beritaTersimpan', 'artikelBerita', 'daftarMatkul'));
    }
    
    public function store(Request $request) {
        berita::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'judul' => $request->judul,
            'sumber' => $request->sumber,
            'url_berita' => $request->url_berita,
            'url_gambar' => $request->url_gambar,
            'published_at' => Carbon::parse($request->published_at),
            'catatan_dosen' => null,
        ]);
        return redirect()->back()->with('success', 'Berita disimpan!');
    }

    public function update(Request $request, $id) {
        $berita = berita::findOrFail($id);
        $berita->update(['catatan_dosen' => $request->catatan_dosen]);
        return redirect()->back();
    }

    public function destroy($id) {
        $berita = berita::findOrFail($id);
        $berita->delete();
        return redirect()->back();
    }

    // --- UPDATE: CETAK SATUAN ---
    public function cetakPdf($id)
    {
        // Load relasi mataKuliah agar namanya bisa diambil di PDF
        $berita = berita::with('mataKuliah')->findOrFail($id);
        
        $pdf = Pdf::loadView('kliping.pdf_template', compact('berita'));
        return $pdf->download('Validasi-'.$berita->id.'.pdf');
    }

    // --- UPDATE: CETAK REKAP DENGAN FILTER ---
    public function cetakSemuaPdf(Request $request)
    {
        $query = berita::with('mataKuliah')->latest();
        
        // Variable untuk Judul Header di PDF
        $infoMatkul = "Semua Mata Kuliah"; 

        if ($request->has('filter_matkul') && $request->filter_matkul != '') {
            $query->where('mata_kuliah_id', $request->filter_matkul);
            
            // Ambil nama matkul untuk dijadikan Judul Header
            $matkulDipilih = MataKuliah::find($request->filter_matkul);
            if($matkulDipilih) {
                $infoMatkul = $matkulDipilih->nama_mk;
            }
        }

        $semuaBerita = $query->get();
        
        // Kirim data berita DAN info nama matkul ke View
        $pdf = Pdf::loadView('kliping.pdf_rekap', compact('semuaBerita', 'infoMatkul'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Rekap-RPS.pdf');
    }
}