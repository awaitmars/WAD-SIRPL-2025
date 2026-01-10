<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru - SI-RP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'); body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 font-sans">

    <div class="min-h-screen flex flex-row-reverse">
        
        <!-- BAGIAN KANAN (FORM) -->
        <div class="w-full md:w-1/2 lg:w-5/12 flex items-center justify-center p-8 bg-white shadow-xl z-10">
            <div class="w-full max-w-md space-y-6">
                
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Tambah Pengguna</h2>
                    <p class="mt-2 text-sm text-slate-500">Daftarkan akun baru untuk Dosen/Staff.</p>
                </div>

                <!-- Notifikasi Sukses -->
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Berhasil!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Notifikasi Error -->
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-8 space-y-5" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap & Gelar</label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-400"></i>
                            </div>
                            <input type="text" name="name" id="name" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="Dr. Budi Santoso, M.T." required value="{{ old('name') }}">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="text-xs font-bold text-slate-500 uppercase">Email Institusi</label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400"></i>
                            </div>
                            <input type="email" name="email" id="email" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="dosen@universitas.ac.id" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="text-xs font-bold text-slate-500 uppercase">Password</label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input type="password" name="password" id="password" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="Minimal 5 karakter" required>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase">Konfirmasi Password</label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-slate-400"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-1">
                            <i class="fas fa-user-plus mr-2"></i> Tambahkan User
                        </button>
                    </div>
                </form>

                <!-- Footer Links: Kembali ke Dashboard & Logout -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col items-center space-y-4">
                    <!-- Kembali ke Dashboard -->
                    <a href="{{ route('jadwal.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke halaman Login
                    </a>
                    
                    
                </div>
            </div>
        </div>

        <!-- BAGIAN KIRI (GAMBAR) -->
        <div class="hidden md:block md:w-1/2 lg:w-7/12 relative bg-slate-900">
            <img class="absolute inset-0 h-full w-full object-cover opacity-50" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Library Background">
            
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900/80 to-indigo-900/50"></div>
            
            <div class="absolute top-0 right-0 p-12 text-right text-white">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20">
                    <i class="fas fa-shield-alt text-indigo-400"></i>
                    <span class="text-xs font-bold tracking-wide uppercase">Admin Area</span>
                </div>
            </div>

            <div class="absolute bottom-0 right-0 p-12 lg:p-16 text-white text-right">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">Manajemen Akses<br>Pengguna</h1>
                <p class="text-slate-300 text-lg ml-auto max-w-md">Tambahkan akun Dosen baru untuk memberikan akses ke sistem penjadwalan.</p>
            </div>
        </div>

    </div>
</body>
</html>