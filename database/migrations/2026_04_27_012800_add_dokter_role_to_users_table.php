<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter the enum to include 'dokter'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','kasir','pasien','dokter') DEFAULT 'pasien'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','kasir','pasien') DEFAULT 'pasien'");
    }
};
