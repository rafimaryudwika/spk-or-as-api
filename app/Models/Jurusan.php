<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table="jurusan";

    public function Fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_f');
    }

    public function Pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }
}
