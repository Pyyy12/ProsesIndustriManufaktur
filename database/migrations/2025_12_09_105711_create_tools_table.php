<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
          $table->string('nomor_alat', 20)->primary();
    $table->string('nama_alat', 100);
    $table->integer('stok');
    $table->string('gambar', 100)->nullable();
    $table->string('kategori', 50)->nullable(); // BARU: Kolom Kategori
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
