<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class berita extends Model
{
    use HasFactory;

    protected $table = 'beritas'; 

    protected $fillable = [
        'judul', 'sumber', 'url_berita', 
        'url_gambar', 'published_at', 'catatan_dosen'
    ];
}