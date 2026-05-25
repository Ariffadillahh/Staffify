<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}
