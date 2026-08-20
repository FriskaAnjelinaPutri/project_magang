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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis_obat');
    }
};
