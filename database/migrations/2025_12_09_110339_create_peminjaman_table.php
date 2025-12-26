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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->string('no_pinjam', 20)->primary();
    $table->string('nim', 15);
    $table->string('nip', 20);
    $table->string('dosen_pengampu', 100);
    $table->string('mata_kuliah', 100);
    $table->date('tanggal');           // DIGANTI: Dari tanggal_date menjadi tanggal
    $table->time('waktu_pinjam');
    $table->time('waktu_kembali')->nullable(); // BARU: Waktu pengembalian (bisa kosong)
    $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat']);
    $table->timestamps();

    // Foreign Key Constraints (Sama seperti sebelumnya)
    $table->foreign('nim')->references('nim')->on('peminjam')->onDelete('cascade');
    $table->foreign('nip')->references('nip')->on('plp')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
