<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jadwal Mengajar</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 2px 0; color: #555; font-size: 10pt; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .text-center { text-align: center; }
        .badge-bentrok { color: red; font-weight: bold; }
        .badge-aman { color: green; font-weight: bold; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10pt; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN JADWAL MENGAJAR & PRAKTIKUM</h2>
        <p>Sistem Informasi Rencana Pembelajaran (SI-RP)</p>
        <p>Dosen: Dr. Budi Santoso, M.T.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Mata Kuliah</th>
                <th width="15%">Jenis</th>
                <th width="15%">Ruangan</th>
                <th width="15%">Waktu</th>
                <th width="20%">Status Validasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $item->mata_kuliah }}</strong><br>
                    <small>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</small>
                </td>
                <td class="text-center">{{ $item->label_jenis }}</td>
                <td class="text-center">{{ $item->ruangan ?? '-' }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                </td>
                <td class="text-center">
                    @if($item->status_validasi_ibadah == 'bentrok')
                        <span class="badge-bentrok">BENTROK</span><br>
                        <small style="color:red">({{ $item->keterangan_konflik }})</small>
                    @elseif($item->status_validasi_ibadah == 'aman')
                        <span class="badge-aman">AMAN</span>
                    @else
                        <span>{{ $item->keterangan_konflik }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data jadwal.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }} WIB</p>
    </div>
</body>
</html>