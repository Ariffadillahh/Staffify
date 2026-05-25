<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $guarded = ['id'];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function jadwalWawancara()
    {
        return $this->belongsTo(JadwalWawancara::class, 'jadwal_wawancara_id');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
