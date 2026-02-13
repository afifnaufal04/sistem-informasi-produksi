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
        Schema::table('progres_produksi', function (Blueprint $table) {
            if (!Schema::hasColumn('progres_produksi', 'dlm_proses')) {
                $table->integer('dlm_proses')->nullable()->after('sub_proses_id');
            }
            if (!Schema::hasColumn('progres_produksi', 'sdh_selesai')) {
                $table->integer('sdh_selesai')->nullable()->after('dlm_proses');
            }
            if (!Schema::hasColumn('progres_produksi', 'jumlah')) {
                $table->integer('jumlah')->nullable()->after('sdh_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progres_produksi', function (Blueprint $table) {
            if (Schema::hasColumn('progres_produksi', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
            if (Schema::hasColumn('progres_produksi', 'sdh_selesai')) {
                $table->dropColumn('sdh_selesai');
            }
            if (Schema::hasColumn('progres_produksi', 'dlm_proses')) {
                $table->dropColumn('dlm_proses');
            }
        });
    }
};
