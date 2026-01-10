<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-RP | Dashboard Jadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .modal-enter { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="flex min-h-screen">
        
        <!-- SIDEBAR (Fixed Left) -->
        <aside class="w-72 bg-slate-900 text-white hidden md:flex flex-col fixed h-full z-20">
            
            <!-- BAGIAN 1: LOGO & MENU -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-8 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-500 rounded-lg"><i class="fas fa-graduation-cap text-xl"></i></div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">SI-RP</h1>
                            <p class="text-slate-400 text-[10px] uppercase font-semibold">Dosen System</p>
                        </div>
                    </div>
                </div>
                
                <nav class="mt-6 px-4 space-y-2">
                    <a href="{{ route('jadwal.index') }}" class="flex items-center px-4 py-3 bg-indigo-600 text-white rounded-lg shadow-lg group">
                        <i class="fas fa-clock mr-3 text-yellow-300"></i>
                        <span class="font-medium">Jadwal & Validasi</span>
                    </a>

                    <a href="{{ route('master-mk.index') }}" class="flex items-center px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition group">
                        <i class="fas fa-book mr-3 group-hover:text-indigo-400 transition"></i>
                        <span class="font-medium">Master MK</span>
                    </a>
                </nav>
            </div>

            <!-- BAGIAN 2: TOMBOL KELUAR (Fixed Bottom) -->
            <div class="p-4 border-t border-slate-800 bg-slate-900">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-slate-800 text-slate-400 hover:bg-rose-600 hover:text-white rounded-xl transition duration-200 group shadow-md border border-slate-700 hover:border-rose-500">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span class="font-bold text-sm">Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT (Offset Right) -->
        <main class="flex-1 md:ml-72 p-8 overflow-y-auto">
            
            <!-- HEADER HALAMAN -->
            <header class="flex justify-between items-center mb-8 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Validasi Waktu Kuliah & Praktikum</h2>
                    <p class="text-sm text-slate-500">Integrasi jadwal akademik dengan API Jadwal Sholat (Aladhan).</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold">{{ Auth::user()->name ?? 'Dosen Tamu' }}</p>
                        <p class="text-xs text-indigo-600">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-50">
                        {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded-r shadow-sm flex items-center justify-between">
                <div>
                    <p class="font-bold">Sukses!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl opacity-50"></i>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded-r shadow-sm">
                <p class="font-bold">Terjadi Kesalahan</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
            @endif

            <!-- API Status Card -->
            @php
                $isApiOnline = true;
                if($errors->any()) {
                    foreach($errors->all() as $err) {
                        if(stripos($err, 'api') !== false || stripos($err, 'connect') !== false) {
                            $isApiOnline = false;
                            break;
                        }
                    }
                }
            @endphp

            <div class="{{ $isApiOnline ? 'bg-indigo-50 border-indigo-100' : 'bg-rose-50 border-rose-100' }} border rounded-2xl p-5 mb-8 flex items-center justify-between transition-colors duration-300">
                <div class="flex items-start space-x-4">
                    <div class="p-3 bg-white rounded-xl shadow-sm">
                        <i class="fas fa-globe-asia {{ $isApiOnline ? 'text-indigo-600' : 'text-rose-500' }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold {{ $isApiOnline ? 'text-indigo-400' : 'text-rose-400' }} uppercase">Status API</p>
                        <p class="{{ $isApiOnline ? 'text-indigo-900' : 'text-rose-900' }} font-medium">
                            Target: <span class="font-bold">api.aladhan.com</span>
                        </p>
                    </div>
                </div>
                
                @if($isApiOnline)
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div> Online
                    </span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full uppercase flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Offline
                    </span>
                @endif
            </div>

            <!-- Action Bar -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Daftar Rencana Saya</h3>
                
                <div class="flex space-x-3">
                    <a href="{{ route('jadwal.exportPdf') }}" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-3 rounded-xl font-bold shadow-lg transition flex items-center transform hover:-translate-y-1">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>
                    <button onclick="openNewPlanModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center transform hover:-translate-y-1">
                        <i class="fas fa-plus-circle mr-2"></i> Rencana Baru
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mata Kuliah & Ruang</th>
                                <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis</th>
                                <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Validasi Ibadah</th>
                                <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($jadwal as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-700 mb-1.5 text-base">{{ $item->mata_kuliah }}</div>
                                    <div class="flex items-center text-xs text-indigo-600 font-bold mb-1">
                                        <i class="fas fa-map-marker-alt mr-2 w-3 text-center"></i>
                                        <span>{{ $item->ruangan ?? 'Belum ditentukan' }}</span>
                                    </div>
                                    <div class="flex items-center text-xs text-slate-500 font-medium">
                                        <i class="far fa-calendar mr-2 w-3 text-center"></i>
                                        <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-[10px] font-bold border px-2 py-1 rounded {{ $item->css_badge }}">
                                        {{ $item->label_jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($item->status_validasi_ibadah == 'aman')
                                        <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase">
                                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                            <span>Aman</span>
                                        </div>
                                    @elseif($item->status_validasi_ibadah == 'bentrok')
                                        <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-[10px] font-bold uppercase">
                                            <div class="w-1.5 h-1.5 bg-red-600 rounded-full animate-ping"></div>
                                            <span>{{ $item->keterangan_konflik }}</span>
                                        </div>
                                    @else
                                        <!-- Menangani status 'Offline' atau 'N/A' dengan warna Abu-abu -->
                                        <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-full text-[10px] font-bold uppercase">
                                            <i class="fas fa-info-circle"></i>
                                            <span>{{ $item->keterangan_konflik }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button 
                                            type="button"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $item->id }}"
                                            data-matkul="{{ $item->mata_kuliah }}"
                                            data-type="{{ $item->type }}"
                                            data-ruangan="{{ $item->ruangan }}" 
                                            data-date="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                            data-start="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                            data-end="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                            class="w-8 h-8 rounded-lg transition flex items-center justify-center 
                                            {{ $item->status_validasi_ibadah == 'bentrok' 
                                                ? 'bg-blue-100 text-blue-600 hover:bg-blue-200 ring-2 ring-blue-500 shadow-sm'  
                                                : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100' 
                                            }}">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </button>
                                        
                                        <form action="{{ route('jadwal.destroy', ['type' => $item->type, 'id' => $item->id]) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition flex items-center justify-center">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada rencana jadwal. Silakan tambah baru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL FORM -->
    <div id="inputModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modalTitle" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="toggleModal('inputModal')"></div>
        <div class="flex min-h-full w-full items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all modal-enter">
                
                <form id="planForm" method="POST" action="{{ route('jadwal.store') }}">
                    @csrf
                    <div id="methodField"></div>
                    <input type="hidden" name="original_type" id="originalTypeInput" value="">

                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Input Rencana Baru</h3>
                        <button type="button" onclick="toggleModal('inputModal')" class="text-slate-400 hover:text-slate-500 focus:outline-none"><i class="fas fa-times"></i></button>
                    </div>

                    <div class="px-6 py-6 space-y-5">
                        <!-- Mata Kuliah -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Mata Kuliah</label>
                            <input type="text" name="mata_kuliah" id="matkulInput" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition" placeholder="Contoh: Pemrograman Web" required>
                        </div>
                        
                        <!-- Jenis Pertemuan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Pertemuan</label>
                            <div class="flex space-x-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="lecture" id="typeLecture" class="peer sr-only" checked>
                                    <div class="p-3 text-center border border-slate-200 rounded-xl peer-checked:bg-indigo-600 peer-checked:text-white transition hover:bg-slate-100 shadow-sm">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> Kuliah
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="practicum" id="typePracticum" class="peer sr-only">
                                    <div class="p-3 text-center border border-slate-200 rounded-xl peer-checked:bg-indigo-600 peer-checked:text-white transition hover:bg-slate-100 shadow-sm">
                                        <i class="fas fa-flask mr-1"></i> Praktikum
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- INPUT RUANGAN -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ruangan</label>
                            <input type="text" name="ruangan" id="ruanganInput" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition" placeholder="Contoh: Lab Komputer 1 atau RK-101" required>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                            <input type="date" name="tanggal" id="dateInput" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition" required>
                        </div>

                        <!-- Waktu -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Mulai</label>
                                <input type="time" name="waktu_mulai" id="startTime" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Selesai</label>
                                <input type="time" name="waktu_selesai" id="endTime" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse border-t border-slate-100">
                        <button type="submit" id="submitBtn" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-500 ml-3 transition">Validasi & Simpan</button>
                        <button type="button" onclick="toggleModal('inputModal')" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('jadwal') }}";

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                modal.classList.toggle("hidden");
                modal.classList.toggle("flex");
            }
        }

        function openNewPlanModal() {
            document.getElementById('planForm').action = "{{ route('jadwal.store') }}";
            document.getElementById('methodField').innerHTML = ''; 
            document.getElementById('modalTitle').innerText = 'Input Rencana Baru';
            document.getElementById('submitBtn').innerText = 'Validasi & Simpan';
            
            document.getElementById('matkulInput').value = '';
            document.getElementById('ruanganInput').value = '';
            document.getElementById('typeLecture').checked = true;
            document.getElementById('originalTypeInput').value = '';
            
            toggleModal('inputModal');
        }

        function openEditModal(btn) {
            const id = btn.dataset.id;
            const matkul = btn.dataset.matkul;
            const type = btn.dataset.type;
            const ruangan = btn.dataset.ruangan;
            const date = btn.dataset.date;
            const start = btn.dataset.start;
            const end = btn.dataset.end;

            const form = document.getElementById('planForm');
            form.action = BASE_URL + "/" + id; 
            
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('modalTitle').innerText = 'Edit Rencana Jadwal';
            document.getElementById('submitBtn').innerText = 'Simpan Perubahan';

            document.getElementById('matkulInput').value = matkul;
            document.getElementById('ruanganInput').value = ruangan;
            document.getElementById('dateInput').value = date;
            document.getElementById('startTime').value = start;
            document.getElementById('endTime').value = end;
            document.getElementById('originalTypeInput').value = type;

            if(type === 'lecture') {
                document.getElementById('typeLecture').checked = true;
            } else {
                document.getElementById('typePracticum').checked = true;
            }

            toggleModal('inputModal');
        }
    </script>
</body>
</html>