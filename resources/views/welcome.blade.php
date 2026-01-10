<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SI-RPL Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #1a2c4d; 
            --sidebar-active: #3b82f6;
            --text-gray: #a0aec0;
        }

        *{
            box-sizing: border-box;
        }
        
        body { 
            background: #f8fafc; 
            margin: 0; 
            font-family: 'Work Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex; 
            min-height: 100vh;
        }

        .sidebar { 
            width: 260px; 
            background: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0;
            min-height: 100vh;
            
        }

          .brand-section {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            background: var(--sidebar-active);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .sidebar-heading {
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-gray);
            padding: 16px 20px 8px;
        }

        .sidebar .nav-link {
            color: var(--text-gray);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(56,189,248,0.15);
            color: white;
            border-left: 4px solid var(--sidebar-active);
        }

        .main-content { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            min-width: 0; 
        }

        .top-navbar { 
            background: white; 
            padding: 0px 40px; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            border-bottom: 1px solid #e2e8f0; 
            height: 70px; 
        }

        .content-body { 
            padding: 30px; 
            flex-grow: 1; 
        }

        /* Perbaikan CSS Kalender agar tidak pecah di dashboard */
        .table-responsive {
            background: white;
            border-radius: 8px;
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('layout.sidebar')

    <main class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-bold small">Admin User</div>
                    <div class="text-secondary" style="font-size: 0.7rem;">Administrator</div>
                </div>
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>