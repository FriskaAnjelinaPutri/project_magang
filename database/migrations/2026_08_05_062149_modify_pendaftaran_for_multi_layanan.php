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
        // 1. Buat tabel pivot
        Schema::create('layanan_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran', 'id_pendaftaran')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('layanan', 'id_layanan')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Pindahkan data yang sudah ada (supaya data lama tidak hilang)
        $pendaftarans = DB::table('pendaftaran')->whereNotNull('id_layanan')->get();
        foreach ($pendaftarans as $p) {
            DB::table('layanan_pendaftaran')->insert([
                'id_pendaftaran' => $p->id_pendaftaran,
                'id_layanan' => $p->id_layanan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Hapus kolom id_layanan dari pendaftaran
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['id_layanan']);
            $table->dropColumn('id_layanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreignId('id_layanan')->nullable()->constrained('layanan', 'id_layanan');
        });

        // Kembalikan data (ambil 1 layanan saja per pendaftaran)
        $pivots = DB::table('layanan_pendaftaran')->groupBy('id_pendaftaran')->get();
        foreach ($pivots as $p) {
            DB::table('pendaftaran')
                ->where('id_pendaftaran', $p->id_pendaftaran)
                ->update(['id_layanan' => $p->id_layanan]);
        }

        Schema::dropIfExists('layanan_pendaftaran');
    }
};
