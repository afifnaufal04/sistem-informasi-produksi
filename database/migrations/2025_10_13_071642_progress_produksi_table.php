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
        Schema::create('progres_produksi', function (Blueprint $table) {
            $table->id('progres_produksi_id');
            $table->foreignId('produksi_id')->constrained('produksi', 'produksi_id')->onDelete('cascade');
            $table->foreignId('sub_proses_id')->constrained('sub_proses', 'sub_proses_id')->onDelete('cascade');
            $table->integer('dlm_proses')->nullable();
            $table->integer('sdh_selesai')->nullable();
            $table->integer('jumlah')->nullable();
            $table->timestamps();

            // Menjamin setiap barang pesanan hanya punya satu entri per sub_proses
            $table->unique(['produksi_id', 'sub_proses_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progres_produksi');
    }
};
