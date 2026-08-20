<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Layanan;
use App\Models\Pendaftaran;
use App\Models\Antrian;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $pasiens = Pasien::all();
        $layanans = Layanan::where('tampil_di_booking', 1)->get();

        if ($pasiens->isEmpty() || $layanans->isEmpty()) {
            $this->command->info('Tidak ada data pasien atau layanan untuk di-seed.');
            return;
        }

        $faker = \Faker\Factory::create('id_ID');

        DB::beginTransaction();

        try {
            foreach ($pasiens as $index => $pasien) {
                // Tanggal kunjungan dari 30 hari yang lalu sampai hari ini
                $tanggal = Carbon::now()->subDays(rand(0, 30));
                
                // Pilih 1 layanan acak
                $layanan = $layanans->random();

                // 1. Buat Pendaftaran
                $pendaftaran = Pendaftaran::create([
                    'id_pasien' => $pasien->id_pasien,
                    'tanggal_kunjungan' => $tanggal->format('Y-m-d'),
                    'status' => 'selesai'
                ]);

                // 2. Hubungkan Layanan
                $pendaftaran->layanans()->attach($layanan->id_layanan);

                // 3. Buat Antrian
                Antrian::create([
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'nomor_antrian' => $index + 1,
                    'tanggal_antrian' => $tanggal->format('Y-m-d'),
                    'status' => 'selesai'
                ]);

                // 4. Buat Rekam Medis
                $biayaTindakan = rand(0, 5) * 50000; // 0, 50k, 100k, dsb
                $biayaObat = rand(1, 10) * 10000; // 10k to 100k
                
                $keluhan = $faker->randomElement([
                    'Gigi ngilu saat minum dingin', 
                    'Gigi berlubang dan sakit', 
                    'Gusi bengkak', 
                    'Ingin membersihkan karang gigi', 
                    'Konsultasi kawat gigi'
                ]);

                $tindakan = $faker->randomElement([
                    'Pemeriksaan dan pemberian obat', 
                    'Tambal gigi sementara', 
                    'Pencabutan gigi sisa akar', 
                    'Scaling (Pembersihan karang gigi)', 
                    'Cetak model gigi'
                ]);

                $resepObat = $faker->randomElement([
                    'Amoxicillin 500mg 3x1, Asam Mefenamat 500mg 3x1', 
                    'Paracetamol 500mg 3x1', 
                    'Ibuprofen 400mg 3x1, Vitamin C 1x1', 
                    'Tidak ada resep'
                ]);

                if ($resepObat == 'Tidak ada resep') {
                    $biayaObat = 0;
                }

                RekamMedis::create([
                    'id_pasien' => $pasien->id_pasien,
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'keluhan' => $keluhan,
                    'tindakan' => $tindakan,
                    'resep_obat' => $resepObat,
                    'biaya_tindakan' => $biayaTindakan,
                    'biaya_obat' => $biayaObat,
                    'tanggal_periksa' => $tanggal->format('Y-m-d')
                ]);

                // 5. Buat Pembayaran
                $totalBayar = $layanan->harga + $biayaTindakan + $biayaObat;
                
                Pembayaran::create([
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'total_bayar' => $totalBayar,
                    'tanggal_pembayaran' => $tanggal->format('Y-m-d'),
                    'status' => 'lunas',
                    'metode_pembayaran' => $faker->randomElement(['cash', 'transfer'])
                ]);
            }

            DB::commit();
            $this->command->info('Berhasil membuat data dummy rekam medis untuk ' . $pasiens->count() . ' pasien.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal membuat data dummy: ' . $e->getMessage());
        }
    }
}
