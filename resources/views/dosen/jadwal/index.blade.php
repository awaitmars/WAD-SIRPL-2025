@extends('layout.main')

@section('title', 'Jadwal & Validasi Dosen')
@section('page_title', 'Jadwal Mata Kuliah') 
@section('page_subtitle', 'Kelola waktu kuliah dan praktikum') 

@section('content')
<style>
    /* CSS Tambahan untuk mengunci layout agar tidak rusak oleh Tailwind Sidebar */
    .main-content-wrapper {
        padding: 2rem;
        /* background-color: #f8f9fa; */
        min-height: 100vh;
    }
    .custom-card {
        background: white !important;
        border-radius: 15px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    }
    .table thead th {
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border: none !important;
    }
    .badge-custom {
        padding: 5px 12px !important;
        font-weight: 800 !important;
        color: #1e293b !important; /* Paksa warna teks gelap */
    }
    /* Memastikan modal muncul di paling depan */
    .modal {
        z-index: 9999 !important;
    }
    .modal-backdrop {
        z-index: 9998 !important;
    }
</style>

<div class="main-content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-3 shadow-sm border">
        <div>
            <h4 class="fw-bold text-dark mb-1">Validasi Waktu Kuliah & Praktikum</h4>
            <p class="text-muted small mb-0">Integrasi jadwal akademik dengan API Jadwal Sholat.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('jadwal.exportPdf') }}" target="_blank" class="btn btn-danger btn-sm px-3 shadow-sm">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <button onclick="openNewPlanModal()" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Rencana Baru
            </button>
        </div>
    </div>

    <div class="card custom-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Mata Kuliah & Ruang</th>
                        <th class="text-center py-3">Jenis</th>
                        <th class="text-center py-3">Waktu</th>
                        <th class="text-center py-3">Validasi</th>
                        <th class="text-end pe-4 py-3">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark">
                                {{ $item->nama_mk}}
                                </div>
                            <div class="small text-muted">
                                <i class="fas fa-map-marker-alt me-1 text-primary"></i>{{ $item->ruangan_kelas ?? $item->ruangan_lab ?? $item->ruangan }}
                            </div>
                        </td>
                        <td class="text-center py-3">
                            <span class="badge badge-custom border {{ $item->css_badge }}">
                                {{ $item->label_jenis }}
                            </span>
                        </td>
                        <td class="text-center py-3">
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</div>
                            <div class="small text-muted text-uppercase" style="font-size: 10px;">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</div>
                        </td>
                        <td class="text-center py-3">
                            @if($item->status_validasi_ibadah == 'aman')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                    <i class="fas fa-check-circle me-1"></i> Aman
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $item->keterangan_konflik }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button onclick="openEditModal(this)" 
                                    data-id="{{ $item->id }}"
                                    data-matkul-id="{{ $item->mata_kuliah_id_for_edit ?? '' }}"
                                    data-type="{{ $item->type }}"
                                    data-ruangan="{{ $item->ruangan_kelas ?? $item->ruangan_lab ?? $item->ruangan }}" 
                                    data-date="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                    data-start="{{ Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                    data-end="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                    class="btn btn-sm btn-light border text-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('jadwal.destroy', ['type' => $item->type, 'id' => $item->id]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Hapus jadwal?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted italic">Belum ada rencana jadwal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="planForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="modal-header border-0 bg-light">
                    <h5 class="fw-bold mb-0" id="modalTitle">Rencana Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Mata Kuliah</label>
                        <select name="mata_kuliah_id" id="matkulInput" class="form-select border-2" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($masterMk as $mk)
                            @if (!empty($mk->kode_mk))
                                <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Jenis Pertemuan</label>
                        <div class="btn-group w-100">
                            <input type="radio" class="btn-check" name="type" id="typeLecture" value="lecture" checked>
                            <label class="btn btn-outline-primary fw-bold" for="typeLecture">KULIAH</label>
                            <input type="radio" class="btn-check" name="type" id="typePracticum" value="practicum">
                            <label class="btn btn-outline-primary fw-bold" for="typePracticum">PRAKTIKUM</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Ruangan</label>
                        <input type="text" name="ruangan" id="ruanganInput" class="form-control border-2" placeholder="Contoh: Ruang 302" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Tanggal Kuliah</label>
                        <input type="date" name="tanggal" id="dateInput" class="form-control border-2" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Jam Mulai</label>
                            <input type="time" name="waktu_mulai" id="startTime" class="form-control border-2" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Jam Selesai</label>
                            <input type="time" name="waktu_selesai" id="endTime" class="form-control border-2" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary px-4 fw-bold shadow-sm">SIMPAN JADWAL</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let myModal;
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi modal secara manual agar tidak bentrok
        myModal = new bootstrap.Modal(document.getElementById('planModal'), {
            backdrop: 'static',
            keyboard: false
        });
    });

    function openNewPlanModal() {
        const form = document.getElementById('planForm');
        form.reset();
        form.action = "{{ route('jadwal.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').innerText = 'Input Rencana Baru';
        myModal.show();
    }

    function openEditModal(btn) {
        const form = document.getElementById('planForm');

        const id      = btn.dataset.id;
        const matkulId = btn.dataset.matkulId;
        const type    = btn.dataset.type;
        const ruangan = btn.dataset.ruangan;
        const date    = btn.dataset.date;
        const start   = btn.dataset.start;
        const end     = btn.dataset.end;

        // Action + method
        form.action = "{{ url('jadwal') }}/" + id;
        document.getElementById('methodField').innerHTML =
            '<input type="hidden" name="_method" value="PUT">' + 
            '<input type="hidden" name="original_type" value="' + type + '">';

        document.getElementById('modalTitle').innerText = 'Edit Rencana';

        document.getElementById('matkulInput').value = matkulId;
        document.getElementById('ruanganInput').value = ruangan;
        document.getElementById('dateInput').value = date;
        document.getElementById('startTime').value = start;
        document.getElementById('endTime').value = end;

        if (type === 'lecture') {
            document.getElementById('typeLecture').checked = true;
        } else {
            document.getElementById('typePracticum').checked = true;
        }

        myModal.show();
    }
</script>
@endpush