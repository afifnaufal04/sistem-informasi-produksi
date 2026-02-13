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
        Schema::create('detail_pengiriman', function (Blueprint $table) {
            $table->id('detail_pengiriman_id');

            $table->foreignId('pengiriman_id')->references('pengiriman_id')->on('pengiriman')->onUpdate('cascade');

            $table->foreignId('produksi_id')
                  ->references('produksi_id')
                  ->on('produksi')
                  ->onUpdate('cascade');

            $table->foreignId('sub_proses_id')
                  ->references('sub_proses_id')
                  ->on('sub_proses')
                  ->onUpdate('cascade');
            
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('jumlah_pengiriman');
            $table->boolean('butuh_bp')->default(false);
            $table->enum('status_pengiriman', ['Belum Diantar', 'Dalam Perjalanan', 'Sampai', 'Diterima'])->default('Belum Diantar');
            $table->timestamp('waktu_sampai')->nullable();
            $table->timestamp('waktu_diterima')->nullable();
            $table->enum('status_pengerjaan', ['Dalam Pengerjaan', 'Selesai', 'Perlu Perbaikan'])->default('Dalam Pengerjaan');
            $table->integer('jumlah_selesai')->default(0);
            $table->boolean('gudang_konfirmasi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengiriman');
    }
};
