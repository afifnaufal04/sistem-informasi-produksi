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
        Schema::create('bahan_pendukung', function (Blueprint $table) {
            $table->id('bahan_pendukung_id');
            $table->string('nama_bahan_pendukung');
            $table->double('stok_bahan_pendukung');
            $table->double('harga_bahan_pendukung');
            $table->string('satuan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_pendukung');
    }
};
