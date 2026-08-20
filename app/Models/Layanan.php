<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $fillable = [
        'nama_layanan',
        'harga',
        'tampil_di_booking',
        'parent_id'
    ];

    public function pendaftarans()
    {
        return $this->belongsToMany(Pendaftaran::class, 'layanan_pendaftaran', 'id_layanan', 'id_pendaftaran');
    }

    public function parent()
    {
        return $this->belongsTo(Layanan::class, 'parent_id', 'id_layanan');
    }

    public function children()
    {
        return $this->hasMany(Layanan::class, 'parent_id', 'id_layanan');
    }
}
