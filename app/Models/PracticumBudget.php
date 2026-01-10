<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticumBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah_id',
        'nama_bahan',
        'jumlah',
        'estimasi_harga',
        'harga_pasar',
        'status',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}