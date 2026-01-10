<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('beritas', function (Blueprint $table) {
            // Menambahkan kolom relasi, diletakkan setelah ID
            $table->unsignedBigInteger('mata_kuliah_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('mata_kuliah_id');
        });
    }
};