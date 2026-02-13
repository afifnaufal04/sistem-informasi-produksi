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
        Schema::create('produksi', function (Blueprint $table) {
            $table->id('produksi_id');
            $table->foreignId('barang_id')->references('barang_id')->on('barang')->onDelete('cascade');
            $table->integer('jumlah_produksi');
            $table->enum('status_produksi', ['Pending', 'Diproses', 'Selesai'])->default('Pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
