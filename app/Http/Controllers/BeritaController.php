<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\berita;
use Barryvdh\DomPDF\Facade\Pdf; // Panggil Facade PDF
use Carbon\Carbon; // Panggil Carbon untuk waktu

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        // Set Locale ke Indonesia agar waktu muncul "30 menit yang lalu"
        Carbon::setLocale('id');

        $beritaTersimpan = berita::latest()->get();
        $artikelBerita = [];

        if ($request->has('keyword') && $request->keyword != null) {
            
            // Logika Filter Waktu (Tetap Sama)
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
                                // Simpan format tanggal asli untuk diproses di View
                                'publishedAt' => (string)$item->pubDate, 
                            ];
                            $limit++;
                        }
                    }
                }
            } catch (\Exception $e) { }
        }

        return view('kliping.index', compact('beritaTersimpan', 'artikelBerita'));
    }

    // Function Store, UpdateNote, Destroy TETAP SAMA (tidak saya tulis ulang agar hemat tempat)
    // Pastikan function store, updateNote, destroy tetap ada di sini ya!
    
    public function store(Request $request) {
        berita::create([
            'judul' => $request->judul,
            'sumber' => $request->sumber,
            'url_berita' => $request->url_berita,
            'url_gambar' => $request->url_gambar,
            'published_at' => Carbon::parse($request->published_at),
            'catatan_dosen' => null,
        ]);
        return redirect()->back()->with('success', 'Berita disimpan!');
    }

    public function updateNote(Request $request, $id) {
        $berita = berita::findOrFail($id);
        $berita->update(['catatan_dosen' => $request->catatan_dosen]);
        return redirect()->back();
    }

    public function destroy($id) {
        $berita = berita::findOrFail($id);
        $berita->delete();
        return redirect()->back();
    }

    // --- BAGIAN BARU: CETAK PDF PER ITEM ---
    public function cetakPdf($id)
    {
        $berita = berita::findOrFail($id);
        
        // Kita load view khusus untuk PDF (nanti kita buat di langkah 4)
        $pdf = Pdf::loadView('kliping.pdf_template', compact('berita'));
        
        // Download PDF dengan nama file yang rapi
        return $pdf->download('Kliping-'.$berita->id.'.pdf');
    }

    public function cetakSemuaPdf()
    {
        // Ambil semua berita, urutkan dari yang terbaru
        $semuaBerita = berita::latest()->get();
        
        // Load view khusus rekap tabel
        $pdf = Pdf::loadView('kliping.pdf_rekap', compact('semuaBerita'));
        
        // Agar tabel muat di PDF, sebaiknya set kertas jadi Landscape
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Rekap-RPS.pdf');
    }
}