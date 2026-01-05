<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('practicum_budgets', function (Blueprint $table) {
        $table->id();
        $table->string('mata_kuliah'); // Contoh: Pengembangan Aplikasi Website
        $table->string('nama_bahan');  // Contoh: PC
        $table->integer('jumlah');
        $table->decimal('estimasi_harga', 15, 2);
        $table->decimal('harga_pasar', 15, 2);
        $table->string('status');      // Aman, Peringatan, Valid
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicum_budgets');
    }
};