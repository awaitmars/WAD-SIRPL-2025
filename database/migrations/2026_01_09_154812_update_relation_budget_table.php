<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practicum_budgets', function (Blueprint $table) {
            // 1. Hapus kolom lama (String manual)
            $table->dropColumn('mata_kuliah');

            // 2. Tambah kolom baru (Relasi ke tabel mata_kuliahs)
            // constrained('mata_kuliahs') artinya nyambung ke id di tabel mata_kuliahs
            $table->foreignId('mata_kuliah_id')->after('id')->constrained('mata_kuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('practicum_budgets', function (Blueprint $table) {
            $table->dropForeign(['mata_kuliah_id']);
            $table->dropColumn('mata_kuliah_id');
            $table->string('mata_kuliah')->nullable();
        });
    }
};