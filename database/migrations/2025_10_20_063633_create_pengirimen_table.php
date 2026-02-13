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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('pengiriman_id');
            
            $table->foreignId('kendaraan_id')->nullable();
            $table->foreign('kendaraan_id')
                    ->references('kendaraan_id')
                    ->on('kendaraan')
                    ->onDelete('cascade');
            
            $table->foreignId('qc_id')->nullable();
            $table->foreign('qc_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('supir_id')->nullable();
            $table->foreign('supir_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->date('tanggal_pengiriman');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Menunggu Gudang','Menunggu QC','Sedang Dipersiapkan','Dalam Pengiriman','Dalam Pengerjaan', 'Selesai'])->default('Menunggu Gudang');
            $table->enum('tipe_pengiriman', ['internal', 'eksternal']);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('total_waktu_antar')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
