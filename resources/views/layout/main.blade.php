<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-RP | @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="{{ asset('css/master-mk.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    
    @stack('styles')
</head>
<body>

    @include('layout.sidebar')

    <main id="main-content">
        <div class="header-container d-flex justify-content-between align-items-start mb-4">
            <div class="header-title">
                <h4 class="fw-bold mb-1">@yield('page_title', 'Dashboard')</h4>
                <p class="text-muted small mb-0">@yield('page_subtitle', 'Validasi Materi & Kliping Berita')</p>
            </div>

        @auth
<div class="custom-dropdown" x-data="{ open: false }">
    <button class="user-profile-card bg-white p-2 px-3 rounded-pill shadow-sm d-flex align-items-center border-0" 
            type="button" 
            @click="open = !open" 
            @click.outside="open = false">
        
        <div class="user-info me-3 text-end d-none d-md-block">
            <div class="fw-bold small text-dark" style="line-height: 1.2;">{{ Auth::user()->name }}</div>
            <div class="text-muted" style="font-size: 10px;">{{ Auth::user()->email }}</div>
        </div>

        <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
            style="width: 35px; height: 35px;">
            <i class="fas fa-user-tie"></i>
        </div>
        <i class="fas fa-chevron-down ms-2 small text-muted"></i>
    </button>
    
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="my-custom-menu shadow border-0 py-2">
        
        <form action="{{ route('logout') }}" method="POST" class="m-0 px-2">
            @csrf
            <button type="submit" class="dropdown-item text-danger small w-100 text-start border-0 bg-transparent d-flex align-items-center">
                <i class="fas fa-sign-out-alt me-2"></i> 
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
@endauth
    </div>

    <div class="content-card">
        @yield('content')
    </div>
</main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    
    {{-- Script Global untuk SweetAlert --}}
    <script>
        $(document).ready(function() {
            

            // Klik di luar menu untuk menutup
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
        @endif
    </script>

    @stack('scripts') {{-- Tempat script khusus halaman (seperti logic Modal/API Wilayah) --}}
</body>
</html>