<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';
    protected $fillable = [ 
        'id_user',
        'nama_pasien',
        'jenis_kelamin',
        'NIK',
        'no_hp',
        'alamat'
    ];
}
