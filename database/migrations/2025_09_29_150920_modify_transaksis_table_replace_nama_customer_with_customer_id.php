<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTransaksisTableReplaceNamaCustomerWithCustomerId extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom nama_customer
            if (Schema::hasColumn('transaksis', 'nama_customer')) {
                $table->dropColumn('nama_customer');
            }

            // Tambah kolom customer_id sebagai foreign key
            $table->foreignId('customer_id')->after('id')->constrained('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom customer_id
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            // Tambah kembali kolom nama_customer
            $table->string('nama_customer')->after('id');
        });
    }
}
