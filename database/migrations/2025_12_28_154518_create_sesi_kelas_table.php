<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sesi_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id')->nullable();
            
            $table->string('mata_kuliah');
            $table->string('ruangan_kelas'); // Pastikan kolom ini ada
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            
            // Kolom Penting untuk API (Penyebab Error Anda)
            $table->string('status_validasi_ibadah')->default('aman'); 
            $table->string('keterangan_konflik')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesi_kelas');
    }
};