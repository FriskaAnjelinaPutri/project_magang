<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE antrian MODIFY status ENUM('belum_datang', 'menunggu','dipanggil','selesai','dilewati') NOT NULL DEFAULT 'belum_datang'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE antrian MODIFY status ENUM('menunggu','dipanggil','selesai','dilewati') NOT NULL DEFAULT 'menunggu'");
    }
};
