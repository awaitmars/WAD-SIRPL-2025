<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPraktikum extends Model
{
     use HasFactory;

    protected $table = 'sesi_praktikums';

    protected $fillable = [
        'dosen_id',
        'mata_kuliah',
        'ruangan_lab',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status_validasi_ibadah',
        'keterangan_konflik',
    ];

    // Casting agar format tanggal/waktu mudah diolah
    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
