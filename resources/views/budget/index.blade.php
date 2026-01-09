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
        formData: { id: '', nama: '', jumlah: '', estimasi: '', matkul: 'Pengembangan Aplikasi Website' },

        openAddModal() {
            this.isEditMode = false;
            this.formAction = '{{ route('budget.store') }}';
            this.formData = { id: '', nama: '', jumlah: '', estimasi: '', matkul: 'Pengembangan Aplikasi Website' };
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
                matkul: item.mata_kuliah 
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
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" class="bg-gray-50 border border-gray-300 rounded-lg pl-10 p-2.5 w-full text-sm" placeholder="Cari Mata Kuliah atau Bahan...">
            </div>

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
            Pengembangan Aplikasi Website
        </h3>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase">
                    <tr>
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
                            </td>
                        </tr>
                    @empty
                        {{-- EMPTY STATE TIDAK DIHAPUS --}}
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                <i class="fas fa-box-open text-3xl mb-2"></i>
                                <div>Belum ada data bahan. Silakan klik "Tambah Bahan".</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL (TETAP) --}}
    {{-- modal code kamu TIDAK diubah, aman --}}
</div>

@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
