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
        Schema::create('sub_proses', function (Blueprint $table) {
            $table->id('sub_proses_id');
            $table->foreignId('proses_id')->constrained('proses', 'proses_id')->onDelete('cascade');
            $table->string('nama_sub_proses'); // e.g., Potong Kayu, Amplas, Cat Dasar
            $table->integer('urutan')->comment('Menentukan alur proses produksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_proses');
    }
};
