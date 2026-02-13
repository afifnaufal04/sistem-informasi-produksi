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
        Schema::create('gudang_qc_gagal', function (Blueprint $table) {
            $table->id('gudang_qc_gagal_id');
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->unsignedBigInteger('sub_proses_id')->nullable();
            $table->integer('jumlah');
            $table->string('asal_spk')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // foreign keys
            $table->foreign('barang_id')
                ->references('barang_id') // ← pastikan sama seperti di tabel barang
                ->on('barang')
                ->onDelete('cascade');

            $table->foreign('sub_proses_id')
                ->references('sub_proses_id')
                ->on('sub_proses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_qc_gagal');
    }
};
