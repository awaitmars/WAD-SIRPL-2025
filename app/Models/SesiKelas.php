<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiKelas extends Model
{
     use HasFactory;

    protected $table = 'sesi_kelas';

    protected $fillable = [
        'dosen_id',
        'mata_kuliah',
        'ruangan_kelas',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status_validasi_ibadah',
        'keterangan_konflik',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];
}
