<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('nama_customer');
        $table->string('alamat')->nullable();
        $table->enum('tipe_pembeli', ['pembeli', 'bengkel_langganan']);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
