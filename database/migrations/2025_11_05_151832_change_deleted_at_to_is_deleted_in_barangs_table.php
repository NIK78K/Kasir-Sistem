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
            $table->dropUnique(['nama_barang', 'deleted_at']);
            $table->dropSoftDeletes();
            $table->boolean('is_deleted')->default(false)->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
            $table->softDeletes();
            $table->unique(['nama_barang', 'deleted_at']);
        });
    }
};
