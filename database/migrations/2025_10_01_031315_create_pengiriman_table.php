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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('pengiriman_id');
            $table->foreignId('id')->constrained('users');
            $table->foreignId('pemesanan_barang_id')->constrained('pemesanan_barang')->onUpdate('cascade');
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onUpdate('cascade')->nullable();
            $table->enum('status', ['pending', 'dikirim', 'dalam proses', 'selesai'])->default('pending');
            $table->date('tanggal_pengiriman')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('main_proses', ['ww', 'finishing', 'packing'])->nullable();
            $table->enum('sub_proses', ['mentah', '2d', '3d', 'laminasi', 'planner', 'lubangi', 'amplas', 'sanding sealer', 'warna', 'wax'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
