@extends('layout.main')

@section('title', 'Dashboard')

@section('content')

        <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Dashboard Terintegrasi</h2>
        <p class="text-muted small">Ringkasan Validasi API & Modul Akademik</p>
    </div>
    <div class="text-end">
        <span class="badge bg-white text-dark shadow-sm p-2">
            <span class="api-indicator bg-online"></span> Semua API Aktif
        </span>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <p class="text-muted small mb-1">Mata Kuliah Diampu</p>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class="fw-bold mb-0">{{ $totalMk ?? 0 }}</h3>
                <i class="fa fa-book text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <p class="text-muted small mb-1">Total Estimasi Anggaran</p>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class="fw-bold mb-0">Rp {{ number_format(($totalAnggaran ?? 0) / 1000000, 1) }}M</h3>
                <i class="fa fa-wallet text-success opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <p class="text-muted small mb-1">Minggu Perkuliahan</p>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class="fw-bold mb-0">12 / 16</h3>
                <i class="fa fa-tasks text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <p class="text-muted small mb-1">Lokasi Lab Tervalidasi</p>
            <div class="d-flex justify-content-between align-items-end">
                <h3 class="fw-bold mb-0">100%</h3>
                <i class="fa fa-map-marker-alt text-danger opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KOLOM KIRI: PERINGATAN & TABEL --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-4"><i class="fa fa-bell me-2 text-warning"></i>Peringatan Jadwal & Ibadah</h5>
            
            {{-- Peringatan dari Fitur Rizal (Kalender) --}}
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-3">
                <i class="fa fa-exclamation-triangle me-3 fa-lg text-warning"></i>
                <div>
                    <strong>Peringatan Kalender:</strong> Pertemuan Minggu ke-13 jatuh pada hari libur nasional. Sistem menyarankan pergeseran jadwal.
                </div>
            </div>

            {{-- Peringatan dari Fitur Dhafa (Validasi Waktu) --}}
            @forelse($bentrokIbadah ?? [] as $bentrok)
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-2">
                <i class="fa fa-clock me-3 fa-lg text-info"></i>
                <div>
                    <strong>Validasi Ibadah:</strong> {{ $bentrok->nama_mata_kuliah }} berpotongan dengan waktu {{ $bentrok->keterangan_konflik }}.
                </div>
            </div>
            @empty
            <div class="alert alert-light border-0 d-flex align-items-center mb-0">
                <i class="fa fa-check-circle me-3 fa-lg text-success"></i>
                <div>Tidak ada konflik jadwal ibadah untuk hari ini.</div>
            </div>
            @endforelse
        </div>

        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3"><i class="fa fa-list-ul me-2 text-primary"></i>Daftar Mata Kuliah Aktif</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama MK</th>
                            <th>Lab / Ruang</th>
                            <th>Status Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mkAktif ?? [] as $mk)
                        <tr>
                            <td>{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td>{{ $mk->ruangan ?? 'N/A' }}</td>
                            <td><span class="badge bg-success">Bandung, Jabar</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data mata kuliah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: NEWS & HARGA --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">
                <i class="fa fa-newspaper me-2"></i>UPDATE MATERI TERKINI
            </h6>
            @foreach($isuTerbaru ?? [] as $isu)
            <div class="news-item mb-3 pb-2 border-bottom">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <a href="#" class="text-decoration-none fw-bold text-dark small d-block">{{ $isu->judul }}</a>
                        <small class="text-muted" style="font-size: 10px;">{{ $isu->sumber }} • {{ $isu->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
            <button class="btn btn-light btn-sm w-100 text-muted" style="font-size: 11px;">Lihat Berita Lainnya...</button>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-muted">
                <i class="fa fa-chart-line me-2"></i>ESTIMASI HARGA
            </h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="small">Kertas A4</span>
                <span class="badge bg-danger">Naik 5%</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="small">Kabel LAN Cat6</span>
                <span class="badge bg-success">Stabil</span>
            </div>
            <div class="progress mb-3 mt-2" style="height: 6px;">
                <div class="progress-bar bg-danger" style="width: 75%"></div>
            </div>
            <p class="text-muted" style="font-size: 11px;">*Data ditarik otomatis dari API Harga Komoditas.</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .api-indicator { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .bg-online { background-color: #2ecc71; }
</style>
@endpush