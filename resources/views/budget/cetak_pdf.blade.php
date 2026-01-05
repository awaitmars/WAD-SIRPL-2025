<!DOCTYPE html>
<html>
<head>
	<title>Laporan Anggaran Praktikum</title>
	<style type="text/css">
		body {
			font-family: sans-serif;
            font-size: 10pt;
		}
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            color: #0F2344;
        }
        .header p {
            margin: 2px;
            color: #555;
        }
		table {
			width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
		}
        table th, table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
		table th {
			background-color: #f2f2f2;
            text-align: center;
		}
        .total-row {
            background-color: #e6f3ff;
            font-weight: bold;
        }
        .status-valid { color: green; font-weight: bold; }
        .status-warning { color: red; font-weight: bold; }
        .status-aman { color: orange; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9pt;
        }
	</style>
</head>
<body>

	<div class="header">
        <h1>DAFTAR ANGGARAN PRAKTIKUM</h1>
        <p>Program Studi Rekayasa Perangkat Lunak</p>
        <p>Mata Kuliah: Pengembangan Aplikasi Website</p>
    </div>

    <p>Tanggal Cetak: {{ date('d F Y') }}</p>

	<table>
		<thead>
			<tr>
				<th style="width: 5%">No</th>
				<th>Nama Bahan</th>
				<th style="width: 10%">Jml</th>
				<th>Harga Satuan (Est)</th>
				<th>Harga Satuan (Pasar)</th>
				<th>Total Anggaran</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
            @php $totalKeseluruhan = 0; @endphp
			@foreach($dataAnggaran as $i => $item)
            @php $totalItem = $item->estimasi_harga * $item->jumlah; $totalKeseluruhan += $totalItem; @endphp
			<tr>
				<td style="text-align: center">{{ $i + 1 }}</td>
				<td>{{ $item->nama_bahan }}</td>
				<td style="text-align: center">{{ $item->jumlah }}</td>
				<td>Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</td>
				<td>Rp {{ number_format($item->harga_pasar, 0, ',', '.') }}</td>
				<td>Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
				<td style="text-align: center">
                    @if($item->status == 'Valid') 
                        <span class="status-valid">Valid</span>
                    @elseif($item->status == 'Peringatan')
                        <span class="status-warning">Cek</span>
                    @else
                        <span class="status-aman">Aman</span>
                    @endif
                </td>
			</tr>
			@endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right">TOTAL KESELURUHAN</td>
                <td colspan="2">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
            </tr>
		</tbody>
	</table>

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>Dr. Instan Permata<br>Dosen Pengampu</p>
    </div>

</body>
</html>