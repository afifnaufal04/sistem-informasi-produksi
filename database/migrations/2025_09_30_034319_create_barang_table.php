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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->foreignId('ww_id')->nullable()
                    ->constrained('ww')
                    ->onUpdate('cascade');

            $table->foreignId('finishing_id')->nullable()
                    ->constrained('finishing')
                    ->onUpdate('cascade');

            $table->foreignId('packing_id')->nullable()
                    ->constrained('packing')
                    ->onUpdate('cascade');

            $table->string('nama_barang');
            $table->double('harga_barang');
            $table->integer('stok_gudang')->nullable();
            $table->string('gambar_barang');
            $table->enum('jenis_barang', ['diy', 'superindo', 'pendopo', 'ooa'])->nullable();
            $table->double('panjang');
            $table->double('lebar');
            $table->double('tinggi');
            $table->integer('jumlah_stok_acc')->nullable();
            $table->integer('jumlah_pemesanan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
