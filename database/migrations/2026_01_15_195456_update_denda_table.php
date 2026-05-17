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
        Schema::table('denda', function (Blueprint $table) {
            $table->enum('jenis_denda', ['telat', 'masalah_unit'])->nullable()->after('pengembalian_id');
            $table->integer('jumlah_hari_terlambat')->nullable()->change();
            $table->decimal('tarif_denda_per_hari', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denda', function (Blueprint $table) {
            $table->dropColumn('jenis_denda');
            $table->integer('jumlah_hari_terlambat')->nullable(false)->change();
            $table->decimal('tarif_denda_per_hari', 10, 2)->nullable(false)->change();
        });
    }
};
