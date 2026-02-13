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
            $table->enum('status_order', ['Menunggu', 'Proses Pembelian', 'Barang Diterima'])
                ->default('Menunggu');
            $table->date('tanggal_pembelian');
            $table->double('total_harga')->default(0);
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
