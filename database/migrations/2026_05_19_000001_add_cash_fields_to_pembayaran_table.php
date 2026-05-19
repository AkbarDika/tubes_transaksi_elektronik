<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('uang_diterima', 14, 2)->nullable()->after('jumlah_bayar');
            $table->decimal('kembalian', 14, 2)->nullable()->after('uang_diterima');
            $table->foreignId('petugas_id')
                ->nullable()
                ->after('kembalian')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
            $table->dropColumn(['uang_diterima', 'kembalian', 'petugas_id']);
        });
    }
};
