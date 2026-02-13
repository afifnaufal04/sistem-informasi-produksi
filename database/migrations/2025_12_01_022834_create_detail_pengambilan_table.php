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
        Schema::create('detail_pengambilan', function (Blueprint $table) {
            $table->id('detail_pengambilan_id');
            $table->foreignId('pengambilan_id')->constrained('pengambilan', 'pengambilan_id')->onDelete('cascade');
            $table->foreignId('detail_pengiriman_id')->constrained('detail_pengiriman', 'detail_pengiriman_id')->onDelete('cascade');
            $table->integer('jumlah_diambil');
            $table->integer('harga_produksi')->nullable();
            $table->integer('total_pembayaran')->nullable();
            $table->enum('status_bayar', ['Belum Dibayar', 'Lunas'])->default('Belum Dibayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengambilan');
    }
};
