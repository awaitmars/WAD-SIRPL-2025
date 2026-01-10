<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class berita extends Model
{
    use HasFactory;

    protected $table = 'beritas'; 

    protected $fillable = [
        'mata_kuliah_id', // <--- PENTING: Agar ID Matkul bisa tersimpan
        'judul', 
        'sumber', 
        'url_berita', 
        'url_gambar', 
        'published_at', 
        'catatan_dosen'
    ];

    // --- RELASI KE MASTER MATA KULIAH ---
    // Pastikan nama Model adalah 'MataKuliah'
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}