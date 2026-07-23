<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'id_rekam_medis';

    protected $fillable = [
        'id_pasien',
        'id_pendaftaran',
        'keluhan',
        'tindakan',
        'biaya_tindakan',
        'resep_obat',
        'biaya_obat',
        'tanggal_periksa'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
