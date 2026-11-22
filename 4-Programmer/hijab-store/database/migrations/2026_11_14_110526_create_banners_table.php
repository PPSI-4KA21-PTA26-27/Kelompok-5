<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('badge')->nullable(); // Contoh: KOLEKSI TERBARU 2026
            $table->string('title'); // Contoh: Keanggunan Alami dalam Setiap Lipatan
            $table->text('subtitle')->nullable(); // Deskripsi singkat hero banner
            $table->string('button_text')->default('Eksplorasi Koleksi');
            $table->string('button_link')->nullable(); // Contoh: /katalog
            $table->string('image'); // Path gambar di storage/banners/...
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->boolean('is_active')->default(true); // Status aktif/sembunyikan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};