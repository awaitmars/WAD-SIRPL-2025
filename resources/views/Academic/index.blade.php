@extends('layout.main')

@push('styles')
<style>
    /* UI Kalender */
    .calendar-td { vertical-align: top !important; height: 110px; width: 14.28%; padding: 8px !important; border: 1px solid #dee2e6 !important; }
    .bg-danger-custom { background-color: #fff5f5 !important; }
    .bg-primary-custom { background-color: #f0f7ff !important; }
    .bg-success-custom { background-color: #f2faf2 !important; }
    .event-badge { display: block; text-align: center; padding: 2px; border-radius: 4px; font-size: 0.65rem; margin-top: 4px; font-weight: bold; color: white; }
    .badge-libur { background-color: #dc3545; }
    .badge-kegiatan { background-color: #0d6efd; }
    .badge-matkul { background-color: #198754; }

    /* Fix Print Full Width */
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea { position: absolute; left: 0; top: 0; width: 100% !important; margin: 0 !important; }
        .d-print-none, .btn, .modal { display: none !important; }
        .card { border: 1px solid #000 !important; box-shadow: none !important; width: 100% !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid" id="printableArea">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0 text-dark">Kalender Akademik</h4>
        <div class="d-flex gap-2 d-print-none">
            <button onclick="window.print()" class="btn btn-outline-dark btn-sm px-3 shadow-sm">Cetak</button>
            <button class="btn btn-dark btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Agenda</button>
        </div>
    </div>

    {{-- CARD KALENDER --}}
    <div class="card p-4 shadow-sm border-0 mb-4">
        @php
            $currentDate = \Carbon\Carbon::create($year, $month, 1);
            $prevMonth = $currentDate->copy()->subMonth(); $nextMonth = $currentDate->copy()->addMonth();
        @endphp
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center bg-light rounded-pill px-4 py-1">
                <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="text-dark d-print-none"><i class="fas fa-chevron-left"></i></a>
                <span class="fw-bold mx-4 text-uppercase">{{ $currentDate->translatedFormat('F Y') }}</span>
                <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="text-dark d-print-none"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-light text-center small fw-bold">
                    <tr><th class="text-danger">MIN</th><th>SEN</th><th>SEL</th><th>RAB</th><th>KAM</th><th>JUM</th><th>SAB</th></tr>
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
                                        $dateStr = \Carbon\Carbon::create($year, $month, $currentDay)->format('Y-m-d');
                                        $dayEvents = $allEvents->where('tanggal', $dateStr);
                                        $bg = $dayEvents->where('tipe', 'libur')->first() ? 'bg-danger-custom' : ($dayEvents->where('tipe', 'matkul')->first() ? 'bg-success-custom' : ($dayEvents->where('tipe', 'kegiatan')->first() ? 'bg-primary-custom' : ''));
                                    @endphp
                                    <td class="calendar-td {{ $bg }}">
                                        <div class="text-end small fw-bold">{{ $currentDay++ }}</div>
                                        @foreach($dayEvents as $e)
                                            <div class="event-badge badge-{{ $e->tipe }}">
                                                {{ ($e->tipe == 'matkul' && isset($e->mataKuliah)) ? $e->mataKuliah->nama_mk : $e->nama_kegiatan }}
                                            </div>
                                        @endforeach
                                    </td>
                                @endif
                            @endfor
                        </tr>
                        @if ($currentDay > $daysInMonth) @break @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL AGENDA --}}
    <div class="card shadow-sm border-0 overflow-hidden mb-5">
        <table class="table align-middle mb-0">
            <thead class="table-light small fw-bold">
                <tr><th class="ps-4 py-3">TANGGAL</th><th>AGENDA</th><th class="text-center">TIPE</th><th class="text-center d-print-none">AKSI</th></tr>
            </thead>
            <tbody>
                @foreach($allEvents->sortBy('tanggal') as $e)
                    @if(\Carbon\Carbon::parse($e->tanggal)->format('n') == $month)
                    <tr>
                        <td class="ps-4 small text-muted">{{ \Carbon\Carbon::parse($e->tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="fw-bold">{{ ($e->tipe == 'matkul' && isset($e->mataKuliah)) ? $e->mataKuliah->nama_mk : $e->nama_kegiatan }}</td>
                        <td class="text-center"><span class="badge bg-{{ $e->tipe == 'matkul' ? 'success' : ($e->tipe == 'libur' ? 'danger' : 'primary') }}">{{ ucfirst($e->tipe) }}</span></td>
                        <td class="text-center d-print-none">
                            @if(!isset($e->is_api))
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $e->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('academic.destroy', $e->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                </form>
                            @else
                                <small class="text-muted">API</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="{{ route('academic.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white"><b>Tambah Agenda Baru</b></div>
                <div class="modal-body p-4">
                    <label class="small fw-bold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control mb-3" required>
                    
                    <label class="small fw-bold">Tipe</label>
                    <select name="tipe" class="form-select mb-3" id="tipeInput" onchange="toggleInputs(this.value)">
                        <option value="matkul">Mata Kuliah</option>
                        <option value="kegiatan">Kegiatan Pribadi</option>
                        <option value="libur">Libur</option>
                    </select>

                    <div id="div_matkul">
                        <label class="small fw-bold">Pilih Mata Kuliah</label>
                        <select name="mata_kuliah_id" class="form-select mb-3">
                            <option value="">-- Pilih --</option>
                            @foreach($mata_kuliahs as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="div_kegiatan" style="display:none;">
                        <label class="small fw-bold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control mb-3" placeholder="Contoh: Rapat Dosen">
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-dark w-100">Simpan Agenda</button></div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($allEvents as $e)
    @if(!isset($e->is_api))
    <div class="modal fade" id="editModal{{ $e->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <form action="{{ route('academic.update', $e->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header bg-info text-white"><b>Edit Agenda</b></div>
                    <div class="modal-body p-4">
                        <label class="small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $e->tanggal }}" class="form-control mb-3">
                        
                        <label class="small fw-bold">Nama/Keterangan</label>
                        <input type="text" name="nama_kegiatan" value="{{ $e->nama_kegiatan }}" class="form-control mb-3">
                        
                        <label class="small fw-bold">Tipe</label>
                        <select name="tipe" class="form-select mb-3">
                            <option value="matkul" {{ $e->tipe == 'matkul' ? 'selected' : '' }}>Mata Kuliah</option>
                            <option value="kegiatan" {{ $e->tipe == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="libur" {{ $e->tipe == 'libur' ? 'selected' : '' }}>Libur</option>
                        </select>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-info text-white w-100">Simpan Perubahan</button></div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
    function toggleInputs(val) {
        document.getElementById('div_matkul').style.display = (val === 'matkul') ? 'block' : 'none';
        document.getElementById('div_kegiatan').style.display = (val === 'matkul') ? 'none' : 'block';
    }
</script>
@endsection