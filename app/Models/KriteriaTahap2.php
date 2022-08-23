<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class KriteriaTahap2 extends Model
{
    use Cachable;

    protected $table = 'kriteria_t2';
    protected $primaryKey = 'id_k2';
    protected $fillable = ['id_k2', 'kriteria', 'k_sc', 'kode', 'bobot'];

    public function SubKriteriaTahap2()
    {
        return $this->hasMany(SubKriteriaTahap2::class, 'id_k2');
    }

    public function PenilaianTahap2()
    {
        return $this->hasManyThrough(PenilaianTahap2::class, SubKriteriaTahap2::class, 'id_k2', 'id_sk2');
    }
}
