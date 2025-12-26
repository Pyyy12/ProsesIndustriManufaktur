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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            // Kolom Foreign Key ke Tabel Peminjaman
            $table->string('no_pinjam', 20);
    $table->string('nomor_alat', 20);
    $table->integer('qty');
    $table->string('keterangan', 255)->nullable(); // DIGANTI: Dari kategori menjadi keterangan

    $table->timestamps();

            // Definisi Foreign Key Constraints
            
            // 1. Relasi ke Tabel Peminjaman
            $table->foreign('no_pinjam')
                  ->references('no_pinjam')
                  ->on('peminjaman')
                  ->onDelete('cascade'); // Jika transaksi peminjaman dihapus, detailnya ikut terhapus.

            // 2. Relasi ke Tabel Tools
            $table->foreign('nomor_alat')
                  ->references('nomor_alat')
                  ->on('tools')
                  ->onDelete('cascade'); // Jika alat dihapus, detail peminjaman alat tersebut ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
