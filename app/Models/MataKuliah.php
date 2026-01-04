<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = ['kode_mk', 'nama_mk', 'sks'];

    public function labs()
    {
        return $this->hasMany(Lab::class, 'mata_kuliah_id');
    }
}
