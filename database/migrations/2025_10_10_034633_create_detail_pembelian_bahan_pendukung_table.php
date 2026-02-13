<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian_bahan_pendukung', function (Blueprint $table) {
            $table->id('detail_pembelian_bahan_pendukung_id');

            $table->unsignedBigInteger('pembelian_bahan_pendukung_id');
            $table->unsignedBigInteger('bahan_pendukung_id');

            $table->integer('jumlah_pembelian');
            $table->double('harga_bahan_pendukung');
            $table->double('subtotal');
            $table->timestamps();

            // 🔧 Gunakan nama foreign key lebih pendek agar tidak melebihi batas 64 karakter
            $table->foreign('pembelian_bahan_pendukung_id', 'fk_pembelian_bahan')
                ->references('pembelian_bahan_pendukung_id')
                ->on('pembelian_bahan_pendukung')
                ->onDelete('cascade');

            $table->foreign('bahan_pendukung_id', 'fk_bahan_pendukung')
                ->references('bahan_pendukung_id')
                ->on('bahan_pendukung')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian_bahan_pendukung');
    }
};
