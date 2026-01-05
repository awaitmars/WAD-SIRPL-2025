<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kliping Isu - SI RPL</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F4F7FE;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background-color: #1B1F3D; color: #fff; position: fixed; height: 100vh; left: 0; top: 0; padding-top: 25px; z-index: 1000; }
        .sidebar-brand { font-size: 1.2rem; font-weight: 700; padding: 0 30px 40px; display: flex; align-items: center; gap: 10px; color: #fff; text-decoration: none; }
        .menu-label { font-size: 0.75rem; color: #8C96AD; font-weight: 600; padding: 0 30px 10px; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; padding: 14px 30px; color: #A3AED0; text-decoration: none; transition: 0.3s; font-weight: 500; font-size: 0.95rem; }
        .nav-item i { width: 25px; }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-item.active { color: #fff; background: rgba(255,255,255,0.1); border-left: 4px solid #4318FF; }
        
        /* --- MAIN CONTENT --- */
        .main-content { margin-left: 260px; padding: 30px 40px; }
        
        /* HEADER */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
        .search-top { background: #fff; border-radius: 30px; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); width: 300px; display: flex; align-items: center; }
        .search-top input { border: none; outline: none; margin-left: 10px; width: 100%; }
        
        .page-header { background: #fff; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .page-header h2 { font-size: 1.5rem; font-weight: 700; color: #1B2559; margin: 0; }
        
        /* AREA PENCARIAN (KIRI) */
        .search-area { background: #fff; border-radius: 20px; padding: 25px; height: 100%; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .filter-bar { display: flex; gap: 10px; margin-bottom: 25px; }
        .input-search-custom { background: #F4F7FE; border: none; border-radius: 10px; padding: 12px 20px; width: 100%; }
        .btn-validasi { background: #868CFF; color: white; border: none; border-radius: 10px; padding: 0 25px; font-weight: 600; }

        /* KARTU BERITA */
        .news-card { border: 1px solid #E0E5F2; border-radius: 15px; padding: 15px; margin-bottom: 20px; display: flex; gap: 15px; align-items: center; background: #fff; }
        .news-img { width: 120px; height: 90px; object-fit: cover; border-radius: 10px; }
        .news-content { flex: 1; }
        .news-source { font-size: 0.75rem; font-weight: 700; border: 1px solid #E0E5F2; padding: 4px 10px; border-radius: 8px; display: inline-block; margin-bottom: 8px; }
        .btn-simpan-rps { border: 2px solid #1B2559; background: transparent; color: #1B2559; font-weight: 700; font-size: 0.75rem; padding: 5px 15px; border-radius: 20px; float: right; margin-top: 5px; transition: 0.3s; cursor: pointer;}
        .btn-simpan-rps:hover { background: #1B2559; color: white; }

        /* AREA DATABASE (KANAN) */
        .rps-area { background: #fff; border: 2px solid #4318FF; border-radius: 20px; padding: 25px; min-height: 600px; box-shadow: 0 5px 20px rgba(67, 24, 255, 0.05); }
        .rps-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #F4F7FE; padding-bottom: 15px; }
        
        .btn-action { background: #A3AED0; color: white; border: none; padding: 6px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; transition: 0.3s; }
        .btn-action:hover { background: #2B3674; color: white; }
        .btn-rekap { background-color: #dc3545; } /* Merah untuk PDF Rekap */
        .btn-rekap:hover { background-color: #bb2d3b; }

        .db-item { border: 1px solid #E0E5F2; border-radius: 15px; padding: 20px; margin-bottom: 20px; }
        .catatan-box { background: #E9EDF7; border-radius: 10px; padding: 15px; margin-top: 15px; width: 100%; border: none; font-size: 0.9rem; color: #2B3674; }

        /* LINK JUDUL */
        .judul-link { color: #1B2559; text-decoration: none; transition: 0.2s; }
        .judul-link:hover { color: #4318FF; text-decoration: underline; }

        /* KHUSUS PRINT (Agar saat Print View tampilan bersih) */
        @media print {
            .sidebar, .top-bar, .search-area, .btn-action, .no-print, .btn-simpan-rps { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
            .col-md-5 { width: 100% !important; flex: 0 0 100%; max-width: 100%; }
            .col-md-7 { display: none; }
            .rps-area { border: none; box-shadow: none; }
            body { background-color: white; }
        }
    </style>
</head>
<body>

    <nav class="sidebar">
        <a href="#" class="sidebar-brand"><i class="fas fa-graduation-cap fa-lg"></i> &nbsp; SI-RPL</a>
        <div class="menu-label">Quick Access</div>
        <a href="#" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#" class="nav-item active"><i class="fas fa-newspaper"></i> Kliping Isu</a>
        <a href="#" class="nav-item"><i class="fas fa-calendar-alt"></i> Kalender Akademik</a>
        
        <div style="position: absolute; bottom: 30px; width: 100%;">
            <a href="#" class="nav-item"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </nav>

    <div class="main-content">
        
        <div class="top-bar">
            <div class="text-muted"><i class="fas fa-bars"></i></div>
            <div class="search-top">
                <i class="fas fa-search text-muted"></i>
                <input type="text" placeholder="Type any cryptocurrency...">
            </div>
            <div class="d-flex align-items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=User&background=random" class="rounded-circle" width="35">
            </div>
        </div>

        <div class="page-header">
            <small class="text-secondary fw-bold">Validasi Materi & Kliping Berita</small>
            <h2>Mata Kuliah : Manajemen Rantai Pasok</h2>
            <p class="text-secondary m-0">Minggu 11 : Pengadaan Pemasok</p>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="search-area">
                    
                    <form action="{{ route('kliping.index') }}" method="GET" class="filter-bar">
                        <input type="text" name="keyword" class="input-search-custom" placeholder="Cari Berita (misal: Supply Chain)..." value="{{ request('keyword') }}">
                        
                        <select name="waktu" class="input-search-custom" style="width: 170px;">
                            <option value="1d" {{ request('waktu') == '1d' ? 'selected' : '' }}>24 Jam Terakhir</option>
                            <option value="7d" {{ request('waktu') == '7d' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30d" {{ request('waktu') == '30d' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        </select>

                        <button type="submit" class="btn-validasi"><i class="fas fa-sync-alt"></i> Cari</button>
                    </form>

                    <h6 class="fw-bold mb-4">Hasil Pencarian</h6>

                    @if(isset($artikelBerita) && count($artikelBerita) > 0)
                        @foreach($artikelBerita as $item)
                        <div class="news-card">
                            <img src="{{ $item['urlToImage'] }}" class="news-img">
                            <div class="news-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="news-source">{{ $item['source']['name'] }}</span>
                                    
                                    <small class="text-danger fw-bold" style="font-size: 0.7rem;">
                                        <i class="far fa-clock"></i> 
                                        {{ \Carbon\Carbon::parse($item['publishedAt'])->locale('id')->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <h6 class="fw-bold mt-2 mb-3">
                                    <a href="{{ $item['url'] }}" target="_blank" class="judul-link">
                                        {{ $item['title'] }} <i class="fas fa-external-link-alt fa-xs text-muted"></i>
                                    </a>
                                </h6>
                                
                                <form action="{{ route('kliping.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="judul" value="{{ $item['title'] }}">
                                    <input type="hidden" name="sumber" value="{{ $item['source']['name'] }}">
                                    <input type="hidden" name="url_berita" value="{{ $item['url'] }}">
                                    <input type="hidden" name="url_gambar" value="{{ $item['urlToImage'] }}">
                                    <input type="hidden" name="published_at" value="{{ $item['publishedAt'] }}">
                                    <button type="submit" class="btn-simpan-rps">Simpan ke RPS</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3" style="color:#E0E5F2;"></i>
                            <p>Silakan cari topik berita untuk divalidasi.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-5">
                <div class="rps-area">
                    <div class="rps-header">
                        <h4 class="fw-bold m-0">RPS DATABASE</h4>
                        <div>
                            <button onclick="window.print()" class="btn-action pointer">
                                <i class="fas fa-print"></i> Print View
                            </button>
                            
                            <a href="{{ route('kliping.cetakSemua') }}" class="btn-action btn-rekap">
                                <i class="fas fa-file-pdf"></i> Rekap PDF
                            </a>
                        </div>
                    </div>

                    @foreach($beritaTersimpan as $dbItem)
                    <div class="db-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-bold" style="line-height: 1.4; width: 80%;">
                                <a href="{{ $dbItem->url_berita }}" target="_blank" class="judul-link">
                                    {{ $dbItem->judul }}
                                </a>
                            </h6>
                            
                            <div class="d-flex gap-1">
                                <a href="{{ route('kliping.cetakPdf', $dbItem->id) }}" class="btn btn-outline-danger btn-sm p-1 px-2" title="Download PDF Validasi Satuan" style="border-radius: 8px;">
                                    <i class="fas fa-file-pdf"></i>
                                </a>

                                <form action="{{ route('kliping.destroy', $dbItem->id) }}" method="POST" class="no-print">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-dark p-0 ms-1"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <span class="text-muted small"><i class="fas fa-newspaper"></i> {{ $dbItem->sumber }}</span>
                            <span class="text-muted small">•</span>
                            <span class="text-primary small fw-bold">
                                {{ \Carbon\Carbon::parse($dbItem->published_at)->locale('id')->diffForHumans() }}
                            </span>
                        </div>

                        <label class="small fw-bold text-secondary">CATATAN DOSEN:</label>
                        
                        <form action="{{ route('kliping.update', $dbItem->id) }}" method="POST">
                            @csrf @method('PUT')
                            <textarea name="catatan_dosen" class="catatan-box" rows="3" placeholder="Tulis instruksi validasi...">{{ $dbItem->catatan_dosen }}</textarea>
                            <div class="text-end mt-2 no-print">
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3">Simpan</button>
                            </div>
                        </form>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

</body>
</html>