<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SI-RP Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'); body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 font-sans">

    <div class="min-h-screen flex">
        
        <!-- BAGIAN KIRI: FORM LOGIN -->
        <div class="w-full md:w-1/2 lg:w-5/12 flex items-center justify-center p-8 bg-white shadow-xl z-10">
            <div class="w-full max-w-md space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-6">
                        <i class="fas fa-graduation-cap text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                    <p class="mt-2 text-sm text-slate-500">Silakan masuk untuk mengelola jadwal perkuliahan.</p>
                </div>

                <!-- Notifikasi Sukses (Ditambahkan) -->
                @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r shadow-sm" role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
                        <div>
                            <p class="font-bold">Berhasil</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notifikasi Error -->
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Gagal Masuk</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="text-xs font-bold text-slate-500 uppercase">Email Institusi</label>
                            <div class="mt-1 relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400"></i>
                                </div>
                                <input type="email" name="email" id="email" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="dosen@universitas.ac.id" required autofocus value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="text-xs font-bold text-slate-500 uppercase">Password</label>
                            <div class="mt-1 relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400"></i>
                                </div>
                                <input type="password" name="password" id="password" class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-slate-900">Ingat saya</label>
                        </div>
                    </div>

                    <!-- Button -->
                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transition-all duration-200">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>

                <!-- Footer Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-slate-500">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">Daftar Dosen Baru</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: GAMBAR / BACKGROUND -->
        <div class="hidden md:block md:w-1/2 lg:w-7/12 relative bg-indigo-900">
            <img class="absolute inset-0 h-full w-full object-cover opacity-40 mix-blend-overlay" src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Kampus Background">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-12 lg:p-16 text-white">
                <div class="h-1 w-12 bg-yellow-400 mb-6 rounded-full"></div>
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">Sistem Informasi<br>Rencana Pembelajaran</h1>
                <p class="text-indigo-200 text-lg max-w-lg">Validasi jadwal otomatis dengan waktu ibadah.</p>
            </div>
        </div>

    </div>
</body>
</html>