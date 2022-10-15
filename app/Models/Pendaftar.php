<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Pendaftar extends Model
{
    //
    // use Cachable;

    protected $table = "pendaftar";
    protected $primaryKey = "nim";

    public function Fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_f');
    }
    public function BidangFakultas()
    {
        return $this->belongsTo(BidangFakultas::class, 'id_f');
    }

    public function Jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_j');
    }

    public function Gender()
    {
        return $this->belongsTo(Gender::class, 'id_g');
    }

    public function PesertaTahap1()
    {
        return $this->hasOne(PesertaTahap1::class, 'nim');
    }
    public function PesertaTahap2()
    {
        return $this->hasOne(PesertaTahap2::class, 'nim');
    }
    public function PesertaTahap3()
    {
        return $this->hasOne(PesertaTahap3::class, 'nim');
    }

    public function PenilaianTahap2()
    {
        return $this->hasMany(PenilaianTahap2::class, 'nim', 'nim');
    }
    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class, 'nim', 'nim');
    }
    public function PenilaianTahap3()
    {
        return $this->hasMany(PenilaianTahap3::class, 'nim', 'nim');
    }
}
