<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-RPL - Anggaran Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style> [x-cloak] { display: none !important; } </style>
</head>

<body class="bg-gray-50 flex h-screen overflow-hidden" 
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
      }">

    <aside class="w-64 bg-[#0F2344] text-white flex flex-col shadow-xl z-20">
        <div class="p-6 flex items-center space-x-3 border-b border-gray-700">
            <div class="bg-cyan-500 p-2 rounded-lg">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <span class="text-xl font-bold tracking-wider">SI-RPL</span>
        </div>

        <nav class="flex-1 px-4 mt-6 space-y-2">
            <p class="text-xs text-gray-400 uppercase font-semibold mb-2 px-2">Quick Access</p>
            
            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-home w-5 text-center"></i> <span>Dashboard</span>
            </a>
            
            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-database w-5 text-center"></i> <span>Master Mata Kuliah</span>
            </a>

            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-calendar-alt w-5 text-center"></i> <span>Kalender Akademik</span>
            </a>

            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-600 text-white shadow-md font-medium">
                <i class="fas fa-wallet w-5 text-center"></i> <span>Anggaran Praktikum</span>
            </a>

            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-book w-5 text-center"></i> <span>Jadwal Kelas</span>
            </a>

            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-newspaper w-5 text-center"></i> <span>Klipping Isu</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <a href="#" class="flex items-center space-x-3 p-3 text-red-400 hover:text-red-300 hover:bg-white/5 rounded-lg transition">
                <i class="fas fa-power-off"></i> <span>Log Out</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-50">
        
        <header class="bg-white h-16 flex items-center justify-between px-8 border-b shadow-sm sticky top-0 z-10">
            <div class="flex items-center text-gray-400 text-sm">
                <i class="fas fa-bars mr-4 text-gray-600 cursor-pointer lg:hidden"></i>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right hidden md:block">
                    <div class="text-sm font-bold text-gray-800">Dr. Instan Permata</div>
                    <div class="text-xs text-gray-500">Dosen Pengampu</div>
                </div>
                <img src="https://ui-avatars.com/api/?name=Dr+Instan&background=0D8ABC&color=fff" class="w-9 h-9 rounded-full border-2 border-gray-100 shadow-sm cursor-pointer hover:opacity-80">
            </div>
        </header>

        <div class="p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Anggaran Praktikum</h1>
                <p class="text-gray-500 mt-1">Kelola keuangan dan anggaran kebutuhan praktikum</p>
                <h2 class="text-xl font-bold text-gray-800 mt-8">Daftar Anggaran Praktikum</h2>
            </div>

            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i class="fas fa-times"></i></button>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition" placeholder="Cari Mata Kuliah atau Bahan...">
                    </div>
                    
                    <div class="flex space-x-3 w-full md:w-auto">
                        <button @click="openAddModal()" class="flex-1 md:flex-none bg-white border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white font-bold rounded-lg text-sm px-4 py-2 transition shadow-sm flex items-center justify-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Bahan
                        </button>
                         <a href="{{ route('budget.cetak') }}" target="_blank" class="flex-1 md:flex-none bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm text-center flex items-center justify-center decoration-0">
                            <i class="fas fa-print mr-2"></i> Cetak PDF
                        </a>
                    </div>
                </div>

                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Pengembangan Aplikasi Website</h3>

                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Bahan</th>
                                <th class="px-6 py-4 text-center font-semibold">Jml</th>
                                <th class="px-6 py-4 font-semibold">Hrg. Satuan (Est)</th>
                                <th class="px-6 py-4 font-semibold">Hrg. Satuan (Pasar)</th>
                                <th class="px-6 py-4 font-semibold text-blue-700 bg-blue-50">Total Anggaran</th>
                                <th class="px-6 py-4 text-center font-semibold">Status</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($dataAnggaran as $item)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama_bahan }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->jumlah }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-gray-500">Rp {{ number_format($item->harga_pasar, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 font-bold text-blue-700 bg-blue-50">
                                    Rp {{ number_format($item->estimasi_harga * $item->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'Peringatan')
                                        <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200">Peringatan</span>
                                    @elseif($item->status == 'Valid')
                                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200">Valid</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">Aman</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button @click="openEditModal({{ $item }})" class="p-1.5 bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 rounded-md transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('budget.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data bahan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-md transition" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400 bg-gray-50 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-box-open text-3xl mb-2 text-gray-300"></i>
                                        <span>Belum ada data bahan. Silakan klik tombol "Tambah Bahan".</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

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
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Mata Kuliah</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-book text-gray-400"></i>
                            </div>
                            <input type="text" name="mata_kuliah" x-model="formData.matkul" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none" readonly>
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

</body>
</html>