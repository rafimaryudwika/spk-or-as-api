<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SubKriteriaTahap3 extends Model
{
    use Cachable;

    //
    protected $table = 'sub_kriteria_t3';
    protected $primaryKey = 'id_sk3';
    protected $fillable = ['id_k3', 'id_sk3', 'sub_kriteria', 'sk_sc', 'kode', 'bobot'];


    public function PenilaianTahap3()
    {
        return $this->hasMany(PenilaianTahap3::class, 'id_sk3');
    }

    public function KriteriaTahap3()
    {
        return $this->belongsTo(KriteriaTahap3::class, 'id_k3');
    }
}
