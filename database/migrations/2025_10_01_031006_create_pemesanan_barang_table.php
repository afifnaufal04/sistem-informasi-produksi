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
        Schema::create('pemesanan_barang', function (Blueprint $table) {
            $table->id('pemesanan_barang_id');

            $table->foreignId('pemesanan_id')->references('pemesanan_id')->on('pemesanan')->onDelete('cascade')->onUpdate('cascade')->nullable();

            $table->foreignId('barang_id')->references('barang_id')->on('barang')->onDelete('cascade')->onUpdate('cascade')->nullable();
            
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->integer('jumlah_pemesanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_barang');
    }
};
