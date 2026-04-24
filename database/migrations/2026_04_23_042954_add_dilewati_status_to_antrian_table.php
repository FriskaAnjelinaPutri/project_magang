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
        // Add new enum value 'dilewati' to support no-show skipping.
        // Use raw SQL to avoid doctrine/dbal requirement.
        DB::statement("ALTER TABLE antrian MODIFY status ENUM('menunggu','dipanggil','selesai','dilewati') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum back (will fail if there are existing 'dilewati' rows).
        DB::statement("ALTER TABLE antrian MODIFY status ENUM('menunggu','dipanggil','selesai') NOT NULL DEFAULT 'menunggu'");
    }
};
