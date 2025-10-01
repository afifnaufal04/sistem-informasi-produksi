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
        Schema::create('bahan_pendukung_barang', function (Blueprint $table) {
            $table->id('bahan_dan_barang_id');

            $table->foreignId('bahan_pendukung_id')->references('bahan_pendukung_id')->on('bahan_pendukung')->onUpdate('cascade')->nullable();

            $table->foreignId('barang_id')->references('barang_id')->on('barang')->onUpdate('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_pendukung_barang');
    }
};
