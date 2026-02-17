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
        Schema::create('packing', function (Blueprint $table) {
            $table->id('packing_id');
            $table->foreignId('pemesanan_barang_id')->references('pemesanan_barang_id')->on('pemesanan_barang')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('jumlah_packing');
            $table->integer('jumlah_selesai_packing')->default(0);
            $table->enum('status_packing', ['Dalam Proses', 'Selesai'])->default('Dalam Proses');
            $table->boolean('gudang_konfirmasi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packings');
    }
};
