<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PesertaTahap1 extends Model
{
    use Cachable;
    //
    protected $table = "peserta_t1";
    protected $primarykey = 'nim';

    public function Pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'nim');
    }

    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class);
    }
}
