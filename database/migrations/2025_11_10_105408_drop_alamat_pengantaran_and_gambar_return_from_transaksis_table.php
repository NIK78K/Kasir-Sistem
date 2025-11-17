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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['alamat_pengantaran', 'gambar_return']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('alamat_pengantaran')->nullable()->after('tipe_pembayaran');
            $table->string('gambar_return')->nullable()->after('alasan_return');
        });
    }
};
