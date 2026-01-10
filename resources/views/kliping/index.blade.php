@extends('layout.main')

@section('title', 'Kliping Isu & Validasi')

@section('content')
<style>
    /* --- CSS KHUSUS FITUR KLIPING SAJA --- */
    
    /* Styling Header Halaman */
    .page-header { background: #fff; padding: 25px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
    .page-header h2 { font-size: 1.5rem; font-weight: 700; color: #1B2559; margin: 0; }
    
    /* Styling Area Kiri (Search) & Kanan (RPS) */
    .search-area { background: #fff; border-radius: 20px; padding: 25px; height: 100%; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
    .rps-area { background: #fff; border: 2px solid #4318FF; border-radius: 20px; padding: 25px; min-height: 600px; box-shadow: 0 5px 20px rgba(67, 24, 255, 0.05); }
    .rps-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 2px solid #F4F7FE; padding-bottom: 15px; }

    /* Input & Tombol */
    .input-search-custom { background: #F4F7FE; border: none; border-radius: 10px; padding: 12px 20px; width: 100%; }
    .btn-validasi { background: #868CFF; color: white; border: none; border-radius: 10px; padding: 0 25px; font-weight: 600; }
    
    /* Kartu Berita */
    .news-card { border: 1px solid #E0E5F2; border-radius: 15px; padding: 15px; margin-bottom: 20px; display: flex; gap: 15px; align-items: center; background: #fff; }
    .news-img { width: 120px; height: 90px; object-fit: cover; border-radius: 10px; }
    .news-content { flex: 1; }
    .news-source { font-size: 0.75rem; font-weight: 700; border: 1px solid #E0E5F2; padding: 4px 10px; border-radius: 8px; display: inline-block; margin-bottom: 8px; }
    
    /* Tombol Dropdown Simpan Custom */
    .btn-dropdown-simpan {
        background-color: transparent;
        color: #1B2559;
        border: 2px solid #1B2559;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 5px 15px;
        border-radius: 20px;
        transition: 0.3s;
    }
    .btn-dropdown-simpan:hover, .btn-dropdown-simpan:focus {
        background-color: #1B2559;
        color: white;
        border-color: #1B2559;
    }
    /* Agar menu dropdown rapi */
    .dropdown-menu-custom {
        border-radius: 10px;
        border: 1px solid #E0E5F2;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        font-size: 0.85rem;
        max-height: 200px;
        overflow-y: auto;
    }

    .btn-action { background: #A3AED0; color: white; border: none; padding: 6px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; transition: 0.3s; }
    .btn-action:hover { background: #2B3674; color: white; }
    .btn-rekap { background-color: #dc3545; } 
    .btn-rekap:hover { background-color: #bb2d3b; }

    .db-item { border: 1px solid #E0E5F2; border-radius: 15px; padding: 20px; margin-bottom: 20px; }
    .catatan-box { background: #E9EDF7; border-radius: 10px; padding: 15px; margin-top: 15px; width: 100%; border: none; font-size: 0.9rem; color: #2B3674; }
    .judul-link { color: #1B2559; text-decoration: none; transition: 0.2s; } .judul-link:hover { color: #4318FF; text-decoration: underline; }

    @media print {
        .sidebar, .top-bar, nav, header, .no-print, .btn-dropdown-simpan, .btn-validasi, .search-area { display: none !important; }
        .main-content { margin: 0; padding: 0; width: 100%; }
        .col-md-5 { width: 100% !important; flex: 0 0 100%; max-width: 100%; }
        .col-md-7 { display: none; }
        .rps-area { border: none; box-shadow: none; }
    }
</style>

<div class="page-header">
    <small class="text-secondary fw-bold">Validasi Materi & Kliping Berita</small>
    <h2>Berita Terkini</h2>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="search-area">
            
            <form action="{{ route('kliping.index') }}" method="GET" class="filter-bar" style="display: flex; gap: 10px;">
                <input type="text" name="keyword" class="input-search-custom" placeholder="Cari Berita (misal: Supply Chain)..." value="{{ request('keyword') }}">
                
                <select name="waktu" class="input-search-custom" style="width: 170px;">
                    <option value="1d" {{ request('waktu') == '1d' ? 'selected' : '' }}>24 Jam</option>
                    <option value="7d" {{ request('waktu') == '7d' ? 'selected' : '' }}>7 Hari</option>
                    <option value="30d" {{ request('waktu') == '30d' ? 'selected' : '' }}>30 Hari</option>
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
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item['publishedAt'])->locale('id')->diffForHumans() }}
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
                            
                            <div class="d-flex justify-content-end mt-2">
                                <div class="dropdown">
                                    <button class="btn btn-dropdown-simpan dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Simpan ke RPS
                                    </button>
                                    
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                        <li><h6 class="dropdown-header">Pilih Mata Kuliah:</h6></li>
                                        
                                        @foreach($daftarMatkul as $matkul)
                                        <li>
                                            <button type="submit" name="mata_kuliah_id" value="{{ $matkul->id }}" class="dropdown-item">
                                                {{ $matkul->nama_mk }}
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

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
                <div class="d-flex flex-column">
                    <h4 class="fw-bold m-0">RPS DATABASE</h4>
                    
                    <form action="{{ route('kliping.index') }}" method="GET" class="mt-2">
                        @if(request('keyword')) <input type="hidden" name="keyword" value="{{ request('keyword') }}"> @endif
                        <select name="filter_matkul" class="form-select form-select-sm border-primary text-primary fw-bold" 
                            style="width: 200px; font-size: 0.8rem;" onchange="this.form.submit()">
                            <option value="">-- Tampilkan Semua --</option>
                            @foreach($daftarMatkul as $matkul)
                                <option value="{{ $matkul->id }}" {{ request('filter_matkul') == $matkul->id ? 'selected' : '' }}>
                                    Filter: {{ $matkul->nama_mk }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div>
                    <a href="{{ route('kliping.cetakSemua', ['filter_matkul' => request('filter_matkul')]) }}" class="btn-action btn-rekap">
                        <i class="fas fa-file-pdf"></i> Rekap
                    </a>
                </div>
            </div>

            @foreach($beritaTersimpan as $dbItem)
            <div class="db-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1" style="line-height: 1.4;">
                            <a href="{{ $dbItem->url_berita }}" target="_blank" class="judul-link">
                                {{ $dbItem->judul }}
                            </a>
                        </h6>
                        <span class="badge bg-secondary mb-2" style="font-size: 0.65rem;">
                            <i class="fas fa-book"></i> {{ $dbItem->mataKuliah->nama_mk ?? 'Matkul Umum' }}
                        </span>
                    </div>
                    
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
@endsection