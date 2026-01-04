<!DOCTYPE html>
<html>
<head>
    <title>Laporan Master Data</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .badge { font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">DAFTAR MATA KULIAH DAN LABORATORIUM</h2>
    <table>
        <thead>
            <tr>
                <th>Tipe</th>
                <th>Kode MK</th>
                <th>Nama MK / Lab</th>
                <th>SKS</th>
                <th>Lokasi/Lab</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                @php 
                    $isMK = $item instanceof \App\Models\MataKuliah;
                    $infoLab = $isMK ? $item->labs->first() : $item;
                @endphp
                <tr>
                    <td>{{ $isMK ? 'MK' : 'LAB' }}</td>
                    <td>{{ $isMK ? $item->kode_mk : '-' }}</td>
                    <td>{{ $isMK ? $item->nama_mk : ($item->nama_lab ?? 'Lab') }}</td>
                    <td>{{ $isMK ? $item->sks : '-' }}</td>
                    <td>{{ $infoLab->nama_lab ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>