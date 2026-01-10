<!DOCTYPE html>
<html>
<head>
    <title>Lembar Validasi Berita</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .meta { color: #555; font-size: 0.9rem; margin-bottom: 20px; }
        .content { border: 1px solid #ddd; padding: 20px; }
        .catatan { background-color: #f4f4f4; padding: 15px; margin-top: 20px; border-left: 5px solid #4318FF; }
        .footer { margin-top: 50px; font-size: 0.8rem; text-align: right; color: #888; }
        .label-matkul { 
            background-color: #0d6efd; 
            color: white; 
            padding: 5px 10px; 
            border-radius: 5px; 
            font-size: 10pt;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LEMBAR VALIDASI KLIPING BERITA</h2>
        
        <p>Mata Kuliah: <strong>{{ $berita->mataKuliah->nama_mk ?? 'Umum' }}</strong></p>
    </div>

    <div class="content">
        <span class="label-matkul">{{ $berita->mataKuliah->nama_mk ?? 'Umum' }}</span>
        
        <h3>{{ $berita->judul }}</h3>
        
        <div class="meta">
            <strong>Sumber:</strong> {{ $berita->sumber }} <br>
            <strong>Tanggal Publish:</strong> {{ \Carbon\Carbon::parse($berita->published_at)->translatedFormat('l, d F Y H:i') }} <br>
            <strong>URL Asli:</strong> <a href="{{ $berita->url_berita }}">{{ $berita->url_berita }}</a>
        </div>

        <hr>

        <div class="catatan">
            <strong>CATATAN DOSEN / VALIDASI:</strong>
            <p>{{ $berita->catatan_dosen ?? 'Belum ada catatan validasi.' }}</p>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i') }} <br>
        Sistem Informasi RPL
    </div>
</body>
</html>