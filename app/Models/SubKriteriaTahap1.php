<?php

namespace App\Models;

use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SubKriteriaTahap1 extends Model
{
    use Cachable;

    //
    protected $table = 'sub_kriteria_t1';
    protected $primaryKey = 'id_sk1';

    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class);
    }

    public function KriteriaTahap1()
    {
        return $this->belongsTo(KriteriaTahap1::class, 'id_k1');
    }
}
