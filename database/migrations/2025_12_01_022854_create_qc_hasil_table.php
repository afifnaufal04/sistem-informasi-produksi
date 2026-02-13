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
        Schema::create('qc_hasil', function (Blueprint $table) {
            $table->id('qc_hasil_id');
            $table->foreignId('detail_pengiriman_id')->constrained('detail_pengiriman', 'detail_pengiriman_id')->onDelete('cascade');
            $table->foreignId('pengambilan_id')->nullable()->constrained('pengambilan', 'pengambilan_id')->onDelete('set null');
            $table->foreignId('qc_id')->constrained('users', 'id')->onDelete('cascade');
            $table->date('tanggal_qc');
            $table->integer('jumlah_lolos')->default(0);
            $table->integer('jumlah_gagal')->default(0);
            $table->integer('jumlah_reject')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'selesai', 'perlu_perbaikan'])->default('pending');
            $table->boolean('tombol_aksi')->default(true);
            $table->timestamps();
            
            // Index untuk memastikan 1 detail_pengiriman hanya punya 1 QC hasil
            $table->unique('detail_pengiriman_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_hasil');
    }
};
