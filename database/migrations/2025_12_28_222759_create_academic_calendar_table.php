<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        
        // UBAH BARIS INI MENJADI NULLABLE
        $table->string('nama_kegiatan')->nullable(); 
        
        $table->string('tipe'); // libur, kegiatan, matkul
        $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliahs')->onDelete('cascade');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};