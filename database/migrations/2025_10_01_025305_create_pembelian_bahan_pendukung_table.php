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
        Schema::create('pembelian_bahan_pendukung', function (Blueprint $table) {
            $table->id('pembelian_bahan_pendukung_id');
            $table->foreignId('pembelian_id')->constrained('pembelian')->onUpdate('cascade');
            $table->foreignId('bahan_pendukung_id')->constrained('bahan_pendukung')->onUpdate('cascade');
            $table->integer('jumlah_pembelian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan_pendukung');
    }
};
