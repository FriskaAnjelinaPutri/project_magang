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
        Schema::dropIfExists('rekam_medis_obat');
        Schema::dropIfExists('obat');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate obat
        Schema::create('obat', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('nama_obat');
            $table->string('satuan')->nullable();
            $table->integer('harga')->default(0);
            $table->timestamps();
        });

        // Recreate rekam_medis_obat
        Schema::create('rekam_medis_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rekam_medis');
            $table->unsignedBigInteger('id_obat');
            $table->integer('jumlah')->default(1);
            $table->string('dosis')->nullable();
            $table->timestamps();

            $table->foreign('id_rekam_medis')->references('id_rekam_medis')->on('rekam_medis')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
        });
    }
};
