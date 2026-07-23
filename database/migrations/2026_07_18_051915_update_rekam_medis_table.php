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
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('diagnosa');
            $table->decimal('biaya_tindakan', 15, 2)->default(0)->after('tindakan');
            $table->decimal('biaya_obat', 15, 2)->default(0)->after('resep_obat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->text('diagnosa')->nullable()->after('keluhan');
            $table->dropColumn('biaya_tindakan');
            $table->dropColumn('biaya_obat');
        });
    }
};
