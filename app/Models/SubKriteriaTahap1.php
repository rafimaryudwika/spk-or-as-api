<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SubKriteriaTahap1 extends Model
{
    use Cachable;

    //
    protected $table = 'sub_kriteria_t1';
    protected $primaryKey = 'id_sk1';
    protected $fillable = ['id_k1', 'id_sk1', 'sub_kriteria', 'bobot'];


    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class, 'id_sk1');
    }

    public function KriteriaTahap1()
    {
        return $this->belongsTo(KriteriaTahap1::class, 'id_k1');
    }
}
