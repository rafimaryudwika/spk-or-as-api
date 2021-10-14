<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    //


    protected $table = "pendaftar";
    protected $primaryKey = "nim";

    public function Fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_f');
    }

    public function Jurusan()
    {
        return $this->belongsTo(Jurusan::class,'id_j');
    }

    public function Gender()
    {
        return $this->belongsTo(Gender::class, 'id_g');
    }

    public function PesertaTahap1()
    {
        return $this->hasMany(PesertaTahap1::class);
    }
}
