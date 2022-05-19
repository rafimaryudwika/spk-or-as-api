<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SubKriteriaTahap2 extends Model
{
    use Cachable;

    //
    protected $table = 'sub_kriteria_t2';
    protected $primaryKey = 'id_sk2';
    protected $fillable = ['id_k2', 'id_sk2', 'sub_kriteria', 'bobot'];


    public function PenilaianTahap2()
    {
        return $this->hasMany(PenilaianTahap1::class, 'id_sk2');
    }

    public function KriteriaTahap2()
    {
        return $this->belongsTo(KriteriaTahap1::class, 'id_k2');
    }
}