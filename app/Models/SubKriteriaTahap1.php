<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteriaTahap1 extends Model
{
    //
    protected $table = 'sub_kriteria_t1';
    protected $primarykey = 'id_sk1';

    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class);
    }

    public function KriteriaTahap1()
    {
        return $this->belongsTo(KriteriaTahap1::class, 'id_k1');
    }
}
