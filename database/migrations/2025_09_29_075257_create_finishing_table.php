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
        Schema::create('finishing', function (Blueprint $table) {
            $table->id('finishing_id');
            $table->enum('jenis_finishing', ['DIY', 'Superindo', 'Pendopo', 'OOA']);
            $table->integer('jumlah_barang');
            $table->enum('sub_proses', ['Sanding Sealer', 'Warna', 'Wax']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finishing');
    }
};
