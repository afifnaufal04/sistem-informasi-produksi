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
              ->constrained('users')   // referensi ke tabel users
              ->onDelete('cascade');
            
            $table->string('no_SPK_pembeli');
            $table->date('tanggal_pemesanan');
            $table->string('no_SPK_kwas');
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
