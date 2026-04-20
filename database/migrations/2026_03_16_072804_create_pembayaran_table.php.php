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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->decimal('total_bayar', 15, 2);
            $table->date('tanggal_pembayaran');
            $table->timestamps();
            $table->foreign('id_pendaftaran')
                  ->references('id_pendaftaran')
                  ->on('pendaftaran')
                  ->onDelete('cascade');
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->enum('metode_pembayaran',['cash','transfer']);
            $table->string('bukti_transfer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
