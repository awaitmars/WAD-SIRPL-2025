@extends('layout.main')

@section('title', 'Validasi Jadwal Dosen')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="mb-4">
    <h4 class="fw-bold">Validasi Waktu Kuliah & Praktikum</h4>
    <p class="text-muted mb-0">
        Integrasi jadwal akademik dengan API Jadwal Sholat
    </p>
</div>

{{-- STATUS API --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-satellite-dish text-primary fs-5"></i>
            <div>
                <div class="text-uppercase small text-secondary fw-bold">
                    Status API
                </div>
                <div class="fw-semibold">
                    Target: api.myquran.com
                </div>
            </div>
        </div>

        <span class="badge bg-success">
            <i class="fas fa-circle me-1"></i> Online
        </span>
    </div>
</div>

{{-- DAFTAR RENCANA --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Daftar Rencana Saya</h5>

    <div class="d-flex gap-2">
        <a href="{{ route('jadwal.exportPdf') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <button class="btn btn-primary btn-sm" onclick="openNewPlanModal()">
            <i class="fas fa-plus-circle me-1"></i> Rencana Baru
        </button>
    </div>
</div>

{{-- TABLE --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Mata Kuliah</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $item)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $item->nama_mata_kuliah }}</div>
                        <small class="text-muted">{{ $item->ruangan }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $item->css_badge }}">
                            {{ $item->label_jenis }}
                        </span>
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                    </td>
                    <td class="text-center">
                        {{ ucfirst($item->status_validasi_ibadah) }}
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light border">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Belum ada rencana jadwal
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL INPUT/EDIT (VERSI BOOTSTRAP UNTUK FIX TAMPILAN) --}}
<div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="planForm" method="POST" action="{{ route('jadwal.store') }}">
                @csrf
                <div id="methodField"></div>
                
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0" id="modalTitle">Input Rencana Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Mata Kuliah</label>
                            <select name="mata_kuliah_id" id="matkulInput" class="form-select" required>
                                @if($masterMk->isEmpty())
                                    <option value="" disabled selected>Data Master MK Kosong!</option>
                                @else
                                    <option value="">-- Pilih Mata Kuliah yang Tersedia--</option>
                                    @foreach($masterMk as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                                    @endforeach
                                @endif
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Jenis Pertemuan</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="typeLecture" value="lecture" checked>
                            <label class="btn btn-outline-primary" for="typeLecture">Kuliah</label>

                            <input type="radio" class="btn-check" name="type" id="typePracticum" value="practicum">
                            <label class="btn btn-outline-primary" for="typePracticum">Praktikum</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Ruangan</label>
                        <input type="text" name="ruangan" id="ruanganInput" class="form-control bg-light" placeholder="Contoh: RK-101" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tanggal</label>
                        <input type="date" name="tanggal" id="dateInput" class="form-control bg-light" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Mulai</label>
                            <input type="time" name="waktu_mulai" id="startTime" class="form-control bg-light" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Selesai</label>
                            <input type="time" name="waktu_selesai" id="endTime" class="form-control bg-light" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light p-3">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary px-4 fw-bold">Simpan Rencana</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Modal Bootstrap
    let myModal;
    document.addEventListener('DOMContentLoaded', function() {
        myModal = new bootstrap.Modal(document.getElementById('planModal'));
    });

    function openNewPlanModal() {
        const form = document.getElementById('planForm');
        form.reset();
        form.action = "{{ route('jadwal.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').innerText = 'Input Rencana Baru';
        document.getElementById('submitBtn').innerText = 'Simpan Rencana';
        myModal.show();
    }

    function openEditModal(btn) {
        const form = document.getElementById('planForm');
        form.action = "{{ url('jadwal') }}/" + btn.dataset.id;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').innerText = 'Edit Rencana Jadwal';
        document.getElementById('submitBtn').innerText = 'Update Jadwal';

        // Isi data ke input
        document.getElementById('matkulInput').value = btn.dataset.matkul;
        document.getElementById('ruanganInput').value = btn.dataset.ruangan;
        document.getElementById('dateInput').value = btn.dataset.date;
        document.getElementById('startTime').value = btn.dataset.start;
        document.getElementById('endTime').value = btn.dataset.end;
        
        if(btn.dataset.type === 'lecture') {
            document.getElementById('typeLecture').checked = true;
        } else {
            document.getElementById('typePracticum').checked = true;
        }

        myModal.show();
    }
</script>

<style>
    /* Styling tambahan agar mirip gambar referensi */
    .modal-content { border-radius: 1rem; }
    .form-control { border: 1px solid #e2e8f0; border-radius: 0.5rem; }
    .btn-outline-primary { border-color: #e2e8f0; color: #64748b; }
    .btn-check:checked + .btn-outline-primary { background-color: #0d6efd; border-color: #0d6efd; color: white; }
</style>
@endsection