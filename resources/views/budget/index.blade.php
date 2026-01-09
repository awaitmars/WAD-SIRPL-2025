@extends('layout.main')

@section('title', 'Anggaran Praktikum')
@section('page_title', 'Anggaran Praktikum')
@section('page_subtitle', 'Kelola keuangan dan anggaran kebutuhan praktikum')

@push('styles')
    {{-- Tailwind KHUSUS halaman ini --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')

<div 
    class="bg-white-50"
    x-data="{ 
        showModal: false, 
        isEditMode: false, 
        formAction: '{{ route('budget.store') }}',
        // UBAH 1: Default matkul_id kosong
        formData: { id: '', nama: '', jumlah: '', estimasi: '', matkul_id: '' },

        openAddModal() {
            this.isEditMode = false;
            this.formAction = '{{ route('budget.store') }}';
            // UBAH 2: Reset form menggunakan matkul_id
            this.formData = { id: '', nama: '', jumlah: '', estimasi: '', matkul_id: '' };
            this.showModal = true;
        },

        openEditModal(item) {
            this.isEditMode = true;
            this.formAction = '/anggaran/' + item.id;
            this.formData = { 
                id: item.id, 
                nama: item.nama_bahan, 
                jumlah: item.jumlah, 
                estimasi: item.estimasi_harga,
                // UBAH 3: Ambil ID dari database (item.mata_kuliah_id)
                matkul_id: item.mata_kuliah_id 
            };
            this.showModal = true;
        }
    }"
>

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mt-1">Daftar Anggaran Praktikum</h2>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- ACTION BAR --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            <form action="{{ route('budget.index') }}" method="GET" class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="bg-gray-50 border border-gray-300 rounded-lg pl-10 p-2.5 w-full text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                       placeholder="Cari Mata Kuliah atau Bahan..." autocomplete="off">
            </form>
            <div class="flex gap-3 w-full md:w-auto">
                <button @click="openAddModal()" class="border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white font-bold rounded-lg text-sm px-4 py-2 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Bahan
                </button>

                <a href="{{ route('budget.cetak') }}" target="_blank"
                   class="border border-gray-300 rounded-lg px-4 py-2 text-sm flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak PDF
                </a>
            </div>
        </div>

        <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">
            {{ $judulHalaman }}
        </h3>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Nama Bahan</th>
                        <th class="px-6 py-4 text-center">Jml</th>
                        <th class="px-6 py-4">Hrg. Est</th>
                        <th class="px-6 py-4">Hrg. Pasar</th>
                        <th class="px-6 py-4 bg-blue-50 text-blue-700">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($dataAnggaran as $item)
                        <tr class="hover:bg-gray-50">
                            {{-- KOLOM MATKUL BARU --}}
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $item->mataKuliah->nama_mk ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-medium">{{ $item->nama_bahan }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->estimasi_harga,0,',','.') }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->harga_pasar,0,',','.') }}</td>
                            <td class="px-6 py-4 font-bold bg-blue-50">
                                Rp {{ number_format($item->estimasi_harga * $item->jumlah,0,',','.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'Peringatan')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Peringatan</span>
                                @elseif($item->status == 'Valid')
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Valid</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Aman</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="openEditModal({{ $item }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('budget.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-2 text-red-600"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400 italic">
                                <i class="fas fa-box-open text-3xl mb-2"></i>
                                <div>Belum ada data bahan. Silakan klik "Tambah Bahan".</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL BARU (YANG SUDAH ADA DROPDOWN MATKUL) --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all" @click.away="showModal = false">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800" x-text="isEditMode ? 'Edit Bahan Praktikum' : 'Tambah Bahan Praktikum'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
    
            <form :action="formAction" method="POST" class="p-6">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
    
                <div class="space-y-4">
                    
                    {{-- DROPDOWN MATA KULIAH --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Mata Kuliah</label>
                        <div class="relative">
                            <select name="mata_kuliah_id" x-model="formData.matkul_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($daftarMatkul as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }} ({{ $mk->kode_mk }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-gray-700 text-sm font-semibold mb-1">Nama Bahan</label>
                            <input type="text" name="nama_bahan" x-model="formData.nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Contoh: RAM 8GB" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-1">Jumlah</label>
                            <input type="number" name="jumlah" x-model="formData.jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="0" required>
                        </div>
                    </div>
    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Estimasi Harga Satuan (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <input type="number" name="estimasi_harga" x-model="formData.estimasi" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Masukkan harga..." required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1"><i class="fas fa-info-circle mr-1"></i>Sistem akan memvalidasi dengan API Harga Pasar otomatis.</p>
                    </div>
                </div>
    
                <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-100">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition focus:outline-none">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-md hover:shadow-lg transition focus:outline-none flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush