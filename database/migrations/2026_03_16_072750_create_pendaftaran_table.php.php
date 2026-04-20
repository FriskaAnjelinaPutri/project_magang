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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->unsignedBigInteger('id_pasien');
            $table->date('tanggal_kunjungan');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])
                    ->default('menunggu');
            $table->timestamps();
            $table->foreign('id_pasien')
                  ->references('id_pasien')
                  ->on('pasien')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
