<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalWawancara extends Model
{
    protected $guarded = ['id'];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class, 'jadwal_wawancara_id');
    }
}
