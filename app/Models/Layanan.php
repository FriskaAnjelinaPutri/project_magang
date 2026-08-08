<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $fillable = [
        'nama_layanan',
        'harga'
    ];

    public function pendaftarans()
    {
        return $this->belongsToMany(Pendaftaran::class, 'layanan_pendaftaran', 'id_layanan', 'id_pendaftaran');
    }
}
