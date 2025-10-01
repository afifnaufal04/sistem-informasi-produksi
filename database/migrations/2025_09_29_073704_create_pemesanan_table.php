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

            $table->foreignId('pembeli_id')
              ->constrained('pembeli')   // referensi ke tabel pembeli
              ->onDelete('cascade');
            
            $table->string('no_SPK_pembeli')->nullable();
            $table->date('tanggal_pemesanan');
            $table->string('no_SPK_kwas')->nullable();
            $table->boolean('konfirmasi_marketing')->default(false);
            $table->boolean('konfirmasi_ppic')->default(false);
            $table->boolean('konfirmasi_gudang')->default(false);
            $table->boolean('konfirmasi_finishing')->default(false);
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
