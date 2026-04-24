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
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        // Backfill existing data:
        // - pasien users: username = pasien.NIK
        // - others (admin/kasir): username = email
        // Note: keep as nullable until all rows are filled.
        DB::statement("
            UPDATE users u
            JOIN pasien p ON p.id_user = u.id
            SET u.username = p.NIK
            WHERE u.username IS NULL
        ");

        DB::table('users')
            ->whereNull('username')
            ->update([
                'username' => DB::raw('email'),
            ]);

        // Enforce NOT NULL + UNIQUE without requiring doctrine/dbal
        DB::statement("ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL");
        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_username_unique');
                $table->dropColumn('username');
            });
        }
    }
};
