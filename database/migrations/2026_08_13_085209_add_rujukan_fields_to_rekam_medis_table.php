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
            $table->string('rujukan_dokter')->nullable()->after('biaya_obat');
            $table->string('rujukan_rs')->nullable()->after('rujukan_dokter');
            $table->text('rujukan_diagnosa_sementara')->nullable()->after('rujukan_rs');
            $table->text('rujukan_kasus')->nullable()->after('rujukan_diagnosa_sementara');
            $table->text('rujukan_terapi')->nullable()->after('rujukan_kasus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn([
                'rujukan_dokter',
                'rujukan_rs',
                'rujukan_diagnosa_sementara',
                'rujukan_kasus',
                'rujukan_terapi'
            ]);
        });
    }
};
