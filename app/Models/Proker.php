<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    protected $guarded = ['id'];

    public function divisi()
    {
        return $this->hasMany(Divisi::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
