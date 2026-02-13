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
        Schema::create('history_pemindahan_produksi', function (Blueprint $table) {
            $table->id('history_pemindahan_produksi_id');

            $table->foreignId('produksi_id')->references('produksi_id')->on('produksi')->onUpdate('cascade');

            $table->foreignId('barang_id')->references('barang_id')->on('barang')->onUpdate('cascade');

            $table->integer('jumlah');

            $table->date('tanggal_pemindahan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pemindahan_produksi');
    }
};
