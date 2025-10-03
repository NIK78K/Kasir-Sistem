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
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('kategori')->after('stok')->default('Lainnya');
            $table->integer('harga_grosir')->after('harga')->default(0);
            $table->string('gambar')->nullable()->after('harga_grosir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'harga_grosir', 'gambar']);
        });
    }
};
