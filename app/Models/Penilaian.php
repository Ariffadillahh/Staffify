<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['pendaftaran_id', 'kriteria_id', 'nilai'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
