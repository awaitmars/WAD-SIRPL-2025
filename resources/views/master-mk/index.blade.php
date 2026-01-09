@extends('layout.main')

@section('title', 'Master Mata Kuliah & Lab')
@section('page_title', 'Manajemen Mata Kuliah & Lab')
@section('page_subtitle', 'Data master untuk akademik dan lokasi laboratorium.')

@section('content')

        <div class="content-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <form action="{{ route('master-mk.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari Kode atau Nama MK..." value="{{ request('search') }}" style="width: 250px;">
                    </div>
                    <button type="submit" class="btn btn-light border">Cari</button>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('master-mk.pdf') }}" class="btn btn-light border">
                        <i class="fa fa-print me-1"></i> Export
                    </a>
                    <button type="button" class="btn btn-custom-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fa fa-plus-circle me-1"></i> Tambah Data
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Lab Terkait</th>
                            <th class="text-center">SKS</th>
                            <th>Provinsi</th>
                            <th>Kota/Kab</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            @php 
                                $isMK = $item instanceof \App\Models\MataKuliah;
                                $infoLab = $isMK ? $item->labs->first() : $item;
                            @endphp
                            <tr>
                                <td><span class="badge-type {{ $isMK ? 'badge-mk' : 'badge-lab' }}">{{ $isMK ? 'MK' : 'LAB' }}</span></td>
                                <td class="fw-medium text-uppercase">{{ $isMK ? $item->kode_mk : '-' }}</td>
                                <td class="fw-bold">{{ $isMK ? $item->nama_mk : '-' }}</td>
                                <td>{{ $infoLab->nama_lab ?? '-' }}</td>
                                <td class="text-center">{{ $isMK ? $item->sks : '-' }}</td>
                                <td><small>{{ $infoLab->provinsi ?? '-' }}</small></td>
                                <td><small>{{ $infoLab->kota ?? '-' }}</small></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light border btn-edit" 
                                        data-id="{{ $item->id }}"
                                        data-type="{{ $isMK ? 'mk' : 'lab' }}"
                                        data-kode="{{ $isMK ? $item->kode_mk : '' }}"
                                        data-nama="{{ $isMK ? $item->nama_mk : '' }}"
                                        data-namalab="{{ $infoLab->nama_lab ?? '' }}"
                                        data-sks="{{ $isMK ? $item->sks : '' }}"
                                        data-provinsi="{{ $infoLab->provinsi ?? '' }}"
                                        data-kota="{{ $infoLab->kota ?? '' }}"
                                        data-kapasitas="{{ $infoLab->kapasitas ?? '' }}">
                                        <i class="fa fa-edit text-primary"></i>
                                    </button>
                                    
                                    <form action="{{ route('master-mk.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="type" value="{{ $isMK ? 'mk' : 'lab' }}">
                                        <button type="submit" class="btn btn-sm btn-light border" onclick="return confirm('Hapus data ini?')">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalMaster" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <form id="mainForm" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-header bg-light">
                        <h5 class="fw-bold mb-0" id="modalTitle">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3" id="groupType">
                            <label class="small fw-bold">Pilih Tipe Input</label>
                            <select name="type" id="dataType" class="form-select" onchange="adjustFormFields()">
                                <option value="mk_only">Mata Kuliah</option>
                                <option value="lab_only">Laboratorium</option>
                                <option value="both">Mata Kuliah + Lab</option>
                            </select>
                        </div>

                        <div id="sectionMK">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="small">Kode MK</label>
                                    <input type="text" name="kode_mk" id="in_kode" class="form-control text-uppercase">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="small">Nama Mata Kuliah</label>
                                    <input type="text" name="nama_mk" id="in_nama_mk" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small">SKS (1-3)</label>
                                    <input type="number" name="sks" id="in_sks" class="form-control" min="1" max="3">
                                </div>
                            </div>
                        </div>

                        <div id="sectionLab">
                            <hr>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="small">Nama Laboratorium</label>
                                    <input type="text" name="nama_lab" id="in_nama_lab" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small">Kapasitas</label>
                                    <input type="number" name="kapasitas" id="in_kapasitas" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small">Provinsi</label>
                                    <select name="provinsi" id="provinsi" class="form-select"></select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small">Kota/Kabupaten</label>
                                    <select name="kota" id="kota" class="form-select"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-custom-dark">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>

            async function loadProvinsi() {
                try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`);
                        const data = await res.json();
                        let options = '<option value="">Pilih Provinsi</option>';
                        data.forEach(p => {
                            // Kita simpan Nama di value untuk dikirim ke DB, dan ID di dataset untuk fetch Kota
                            options += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`;
                        });
                        document.getElementById('provinsi').innerHTML = options;
                    } catch (error) {
                        console.error("Gagal load provinsi:", error);
                    }
                }

            document.getElementById('provinsi').addEventListener('change', async function() {
                const selectedOption = this.options[this.selectedIndex];
                const provId = selectedOption.getAttribute('data-id');
        
                if (!provId) return;

                try {
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`);
                    const data = await res.json();
                    let options = '<option value="">Pilih Kota/Kab</option>';
                    data.forEach(k => {
                        options += `<option value="${k.name}">${k.name}</option>`;
                    });
                    document.getElementById('kota').innerHTML = options;
                } catch (error) {
                    console.error("Gagal load kota:", error);
                }
            });

            window.onload = loadProvinsi;

            // --- 2. MODAL & FORM LOGIC ---
            function adjustFormFields() {
                const type = document.getElementById('dataType').value;
                document.getElementById('sectionMK').style.display = (type === 'lab_only') ? 'none' : 'block';
                document.getElementById('sectionLab').style.display = (type === 'mk_only') ? 'none' : 'block';
            }

            // Handle Tombol Tambah
            document.querySelector('[data-bs-target="#modalTambah"]')?.addEventListener('click', () => {
                document.getElementById('modalTitle').innerText = "Tambah Data Baru";
                document.getElementById('mainForm').action = "{{ route('master-mk.store') }}";
                document.getElementById('methodField').innerHTML = "";
                document.getElementById('groupType').style.display = "block";
                document.getElementById('mainForm').reset();
                adjustFormFields();
                new bootstrap.Modal(document.getElementById('modalMaster')).show();
            });

            // Handle Tombol Edit
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const d = this.dataset;
                    const form = document.getElementById('mainForm');

                    document.getElementById('modalTitle').innerText = "Update Data";
                    document.getElementById('mainForm').action = "{{ url('master-mk') }}/" + d.id;
                    document.getElementById('methodField').innerHTML = '@method("PUT")';
                    document.getElementById('groupType').style.display = "none";
                    
                    let typeInput = document.getElementById('in_type_hidden');
                    if (!typeInput) {
                        typeInput = document.createElement('input');
                        typeInput.type = 'hidden';
                        typeInput.name = 'type';
                        typeInput.id = 'in_type_hidden';
                        form.appendChild(typeInput);
                    }

                    typeInput.value = d.type;

                    document.getElementById('in_kode').value = d.kode;
                    document.getElementById('in_nama_mk').value = d.nama;
                    document.getElementById('in_nama_lab').value = d.namalab;
                    document.getElementById('in_sks').value = d.sks;
                    document.getElementById('in_kapasitas').value = d.kapasitas;
                    document.getElementById('provinsi').value = d.provinsi || '';git
                    document.getElementById('kota').value = d.kota || '';

                    if (d.type === 'mk') {
                        document.getElementById('sectionMK').style.display = 'block';
                        document.getElementById('sectionLab').style.display = (d.namalab !== '') ? 'block' : 'none';
                    } else {
                        document.getElementById('sectionMK').style.display = 'none';
                        document.getElementById('sectionLab').style.display = 'block';
                    }

                    new bootstrap.Modal(document.getElementById('modalMaster')).show();
                });
            });

            window.onload = loadProvinsi;
        </script>
        <script>
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: `
                        <ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonColor: '#1F263E'
                });
                
                var myModal = new bootstrap.Modal(document.getElementById('modalMaster'));
                myModal.show();
            @endif

            // 2. Pop-up jika Berhasil (Success Session)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        </script>
        @endpush