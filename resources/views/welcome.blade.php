<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SI-RPL Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #1a2c4d; 
            --sidebar-active: #3b82f6;
            --text-gray: #a0aec0;
        }
        body { background: #f8fafc; display: flex; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif; }
        
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-brand { padding: 25px; display: flex; align-items: center; gap: 10px; font-size: 1.25rem; font-weight: bold; background: rgba(0,0,0,0.1); }
        .sidebar-content { flex-grow: 1; padding: 20px 15px; }
        .nav-label { color: var(--text-gray); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 10px 15px 5px; }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--text-gray); text-decoration: none; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.05); }
        .nav-link.active { background: var(--sidebar-active); color: white; font-weight: 500; }
        
        .logout-section { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { color: #f87171; display: flex; align-items: center; gap: 10px; text-decoration: none; padding: 10px 15px; }
        
        .main-content { flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; }
        .top-navbar { background: white; padding: 10px 40px; display: flex; justify-content: flex-end; align-items: center; border-bottom: 1px solid #e2e8f0; height: 70px; }
        .content-body { padding: 30px; flex-grow: 1; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="bg-info rounded p-1"><i class="fas fa-user-graduate text-white"></i></div> SI-RPL
        </div>
        <div class="sidebar-content">
            <div class="nav-label">Main Menu</div>
            <a href="#" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#" class="nav-link"><i class="fas fa-database"></i> Master Mata Kuliah</a>
            <a href="{{ route('academic.index') }}" class="nav-link active"><i class="fas fa-calendar-alt"></i> Kalender Akademik</a>
            <a href="#" class="nav-link"><i class="fas fa-wallet"></i> Anggaran Praktikum</a>
            <a href="#" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> Jadwal Kelas</a>
            <a href="#" class="nav-link"><i class="fas fa-newspaper"></i> Klipping Isu</a>
        </div>
        <div class="logout-section"><a href="#" class="btn-logout"><i class="fas fa-power-off"></i> Log Out</a></div>
    </aside>

    <main class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-bold small">Admin User</div>
                    <div class="text-secondary" style="font-size: 0.7rem;">Administrator</div>
                </div>
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;"><i class="fas fa-user"></i></div>
            </div>
        </header>
        <div class="content-body">@yield('content')</div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>