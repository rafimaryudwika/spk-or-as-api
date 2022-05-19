<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PesertaTahap2 extends Model
{
    use Cachable;
    //
    protected $table = "peserta_t2";
    protected $primarykey = 'nim';

    public function PesertaTahap1()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim');
    }

    public function PenilaianTahap2()
    {
        return $this->hasMany(PenilaianTahap2::class);
    }
}
