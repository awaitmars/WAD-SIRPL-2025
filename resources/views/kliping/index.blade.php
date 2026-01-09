@extends('layout.main')
@section('title', 'Kliping Isu Terkini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kliping.css') }}">
@endpush

@section('content')        
        {{-- <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=User&background=random" class="rounded-circle" width="35">
            </div>
        </div> --}}

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
@endsection
