<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticumBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah',
        'nama_bahan',
        'jumlah',
        'estimasi_harga',
        'harga_pasar',
        'status',
    ];
}