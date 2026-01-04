@extends('welcome')

@section('content')
<style>
    /* UI Kalender */
    .calendar-td { height: 85px; vertical-align: middle; width: 14.28%; border: 1px solid #f0f0f0 !important; }
    
    /* Warna Merah (Libur) */
    .bg-danger-custom { 
        background-color: #dc3545 !important; color: white !important; font-weight: bold; 
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    
    /* Warna Biru (Kegiatan) */
    .bg-primary-custom { 
        background-color: #0d6efd !important; color: white !important; font-weight: bold; 
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }

    .card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    @media print {
        .sidebar, .top-navbar, .btn, .logout-section, .nav-label, .btn-close, .d-print-none { display: none !important; }
        .main-content { padding: 0 !important; margin: 0 !important; width: 100% !important; }
        .bg-danger-custom { background-color: #dc3545 !important; color: white !important; border: 1px solid #dc3545 !important; }
        .bg-primary-custom { background-color: #0d6efd !important; color: white !important; border: 1px solid #0d6efd !important; }
        .table th, .table td { border: 1px solid #dee2e6 !important; padding: 8px !important; }
    }
</style>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h4 class="fw-bold m-0 text-dark">Kalender Akademik</h4>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-dark btn-sm px-3"><i class="fas fa-print me-1"></i> Cetak</button>
            <button type="button" class="btn btn-dark btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Hari Libur/Kegiatan
            </button>
        </div>
    </div>

    <div class="card p-4">
        @php
            $currentDate = \Carbon\Carbon::create($year, $month, 1);
            $prevMonth = $currentDate->copy()->subMonth();
            $nextMonth = $currentDate->copy()->addMonth();

            $normalizedEvents = [];
            foreach($eventDates as $date => $tipe) {
                try {
                    $cleanDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                    $normalizedEvents[$cleanDate] = $tipe;
                } catch(\Exception $e) { continue; }
            }
        @endphp
        
        <div class="text-center mb-4">
            <h5 class="fw-bold d-none d-print-block">KALENDER AKADEMIK - {{ strtoupper($currentDate->translatedFormat('F Y')) }}</h5>
            <div class="d-inline-flex align-items-center bg-light rounded-pill px-4 py-1 d-print-none">
                <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="text-dark text-decoration-none"><i class="fas fa-chevron-left"></i></a>
                <span class="fw-bold mx-4" style="min-width: 150px;">{{ $currentDate->translatedFormat('F Y') }}</span>
                <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="text-dark text-decoration-none"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-light">
                    <tr><th class="text-danger py-3">Min</th><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th></tr>
                </thead>
                <tbody>
                    @php $daysInMonth = $currentDate->daysInMonth; $dayOfWeek = $currentDate->dayOfWeek; $currentDay = 1; @endphp
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            @for ($j = 0; $j < 7; $j++)
                                @if (($i === 0 && $j < $dayOfWeek) || $currentDay > $daysInMonth)
                                    <td class="bg-light calendar-td"></td>
                                @else
                                    @php
                                        $checkDate = \Carbon\Carbon::create($year, $month, $currentDay)->format('Y-m-d');
                                        $tipe = $normalizedEvents[$checkDate] ?? null;
                                        $bgClass = ($tipe == 'libur') ? 'bg-danger-custom' : (($tipe == 'kegiatan') ? 'bg-primary-custom' : '');
                                    @endphp
                                    <td class="calendar-td {{ $bgClass }}">{{ $currentDay++ }}</td>
                                @endif
                            @endfor
                        </tr>
                        @if ($currentDay > $daysInMonth) @break @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <h6 class="fw-bold mb-3 text-secondary small text-uppercase">Daftar Kegiatan & Libur Nasional</h6>
        <div class="card overflow-hidden border-0 shadow-sm">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr class="small">
                        <th class="ps-4 py-3">TANGGAL</th><th class="py-3">KEGIATAN</th><th class="text-center py-3">TIPE</th><th class="text-center py-3 d-print-none">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        @if(\Carbon\Carbon::parse($event->tanggal)->format('m') == $month && \Carbon\Carbon::parse($event->tanggal)->format('Y') == $year)
                        <tr>
                            <td class="ps-4 small text-muted">{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="fw-bold">{{ $event->nama_kegiatan }}</td>
                            <td class="text-center">
                                <span class="badge {{ $event->tipe == 'libur' ? 'bg-danger' : 'bg-primary' }} px-3">{{ $event->tipe }}</span>
                            </td>
                            <td class="text-center d-print-none">
                                <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $event->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('academic.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade d-print-none" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('academic.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Libur Lebaran / UTS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tipe Jadwal</label>
                        <select name="tipe" class="form-select" required>
                            <option value="libur">Libur (Merah)</option>
                            <option value="kegiatan">Kegiatan (Biru)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($events as $event)
<div class="modal fade d-print-none" id="editModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <form action="{{ route('academic.update', $event->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $event->tanggal }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" value="{{ $event->nama_kegiatan }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Tipe</label>
                        <select name="tipe" class="form-select">
                            <option value="libur" {{ $event->tipe == 'libur' ? 'selected' : '' }}>Libur (Merah)</option>
                            <option value="kegiatan" {{ $event->tipe == 'kegiatan' ? 'selected' : '' }}>Kegiatan (Biru)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection