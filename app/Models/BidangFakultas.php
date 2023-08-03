<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BidangFakultas extends Model
{
    use Cachable;

    // protected $appends = ['n_pendaftar'];
    protected $table = "bidang_fak";
    protected $primaryKey = "id_bf";

    public function PesertaTahap1()
    {
        return $this->hasManyThrough(PesertaTahap1::class, Pendaftar::class);
    }
    public function Pendaftar()
    {
        return $this->hasManyThrough(Pendaftar::class, Fakultas::class, 'id_f', 'id_f');
    }
    public function Fakultas()
    {
        return $this->hasMany(Fakultas::class, 'id_bf');
    }
    public function Jurusan()
    {
        return $this->hasManyThrough(Jurusan::class, Fakultas::class, 'id_f', 'id_f');
    }
    public function getNPendaftarAttribute()
    {
        return $this->Fakultas->sum('total');
    }
}
