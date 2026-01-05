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
    Schema::create('beritas', function (Blueprint $table) {
        $table->id();
        
        $table->string('judul');
        $table->string('sumber');      
        $table->text('url_berita');    
        $table->text('url_gambar')->nullable(); 
        $table->dateTime('published_at')->nullable(); 

        
        $table->text('catatan_dosen')->nullable(); 

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
