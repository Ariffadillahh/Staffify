<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $guarded = ['id'];

    public function proker()
    {
        return $this->belongsTo(Proker::class);
    }

    public function kadiv()
    {
        return $this->belongsTo(User::class, 'kadiv_id');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class);
    }
    
    public function jadwal()
    {
        return $this->hasMany(JadwalWawancara::class);
    }
}
