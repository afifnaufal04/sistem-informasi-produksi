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
        Schema::create('ww', function (Blueprint $table) {
            $table->id('ww_id');
            $table->integer('jumlah_barang')->nullable();
            $table->enum('sub_proses', ['Mentah', '2D', '3D', 'Laminasi', 'Planner', 'Lubangi', 'Amplas'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ww');
    }
};
