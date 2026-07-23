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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam_medis');
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_pendaftaran')->nullable();
            $table->text('keluhan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('resep_obat')->nullable();
            $table->date('tanggal_periksa');
            $table->timestamps();

            $table->foreign('id_pasien')->references('id_pasien')->on('pasien')->onDelete('cascade');
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
