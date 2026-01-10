<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekapitulasi RPS</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { 
            background-color: #e9ecef; 
            padding: 2px 5px; 
            border-radius: 4px; 
            font-size: 8pt; 
            display: inline-block;
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin-bottom: 5px;">REKAPITULASI VALIDASI MATERI & KLIPING BERITA</h2>
        <p style="margin-top: 0; font-size: 12pt;">Mata Kuliah: <strong>{{ $infoMatkul }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Mata Kuliah</th> <th width="20%">Judul Berita</th>
                <th width="15%">Sumber & Waktu</th>
                <th width="20%">URL Berita</th>
                <th width="25%">Catatan Dosen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semuaBerita as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <span class="badge">
                        {{ $item->mataKuliah->nama_mk ?? 'Matkul Umum' }}
                    </span>
                </td>
                <td>{{ $item->judul }}</td>
                <td>
                    <strong>{{ $item->sumber }}</strong><br>
                    <small>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') }}</small>
                </td>
                <td>
                    <a href="{{ $item->url_berita }}" style="word-break: break-all; font-size: 8pt; text-decoration: none; color: blue;">
                        Link Berita
                    </a>
                </td>
                <td>
                    {{ $item->catatan_dosen ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
        <br><br><br>
        <p>( Dosen Pengampu )</p>
    </div>
</body>
</html>