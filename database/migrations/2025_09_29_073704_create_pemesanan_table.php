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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('pemesanan_id');
            
            $table->foreignId('pembeli_id')->references('pembeli_id')->on('pembeli')->onUpdate('cascade');
            
            $table->string('no_PO')->nullable();
            $table->date('tanggal_pemesanan');
            $table->string('no_SPK_kwas')->nullable();
            $table->date('periode_produksi')->nullable();
            $table->date('tgl_penerbitan_spk');
            $table->enum('status_pemesanan', ['diproses', 'selesai'])->default('diproses');
            $table->boolean('konfirmasi_marketing')->default(false);
            $table->boolean('konfirmasi_ppic')->default(false);
            $table->boolean('konfirmasi_gudang')->default(false);
            $table->boolean('konfirmasi_finishing')->default(false);
            $table->boolean('konfirmasi_keprod')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
