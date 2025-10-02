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

            // PERBAIKAN: Gunakan BIGINT eksplisit agar sesuai dengan tipe data ID
            $table->unsignedBigInteger('ww_id')->nullable(); 
            $table->foreign('ww_id')->references('ww_id')->on('ww')->onUpdate('cascade');

            $table->unsignedBigInteger('finishing_id')->nullable(); 
            $table->foreign('finishing_id')->references('finishing_id')->on('finishing')->onUpdate('cascade');
            
            $table->unsignedBigInteger('packing_id')->nullable(); 
            $table->foreign('packing_id')->references('packing_id')->on('packing')->onUpdate('cascade');
        

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
