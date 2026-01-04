<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model {
    protected $fillable = ['tanggal', 'nama_kegiatan', 'tipe'];
}