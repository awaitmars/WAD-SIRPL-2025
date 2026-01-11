<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sesi_praktikums', function (Blueprint $table) {
            $table->id();
            // ID Dosen (Diasumsikan ada tabel users/dosen)
            $table->unsignedBigInteger('dosen_id')->nullable(); 
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('ruangan_lab'); // Pastikan kolom ini ada untuk menampung input ruangan
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            
            // Kolom Wajib untuk fitur API (Sama seperti sesi_kelas)
            $table->string('status_validasi_ibadah')->default('aman'); 
            $table->string('keterangan_konflik')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesi_praktikums');
    }
};