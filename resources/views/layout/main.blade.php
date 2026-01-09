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
    @stack('styles') {{-- Untuk CSS tambahan jika anggota butuh --}}
</head>
<body>

    @include('layout.sidebar')

    <main id="main-content">
        <div class="header-title mb-4">
            <h4 class="fw-bold mb-1">@yield('page_title')</h4>
            <p class="text-muted small">@yield('page_subtitle')</p>
        </div>

        <div class="content-card">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Script Global untuk SweetAlert --}}
    <script>
        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
        @endif
    </script>

    @stack('scripts') {{-- Tempat script khusus halaman (seperti logic Modal/API Wilayah) --}}
</body>
</html>