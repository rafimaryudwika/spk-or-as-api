<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianTahap1 extends Model
{
    //
    protected $table="nilai_t1";

    public function PesertaTahap1()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim');
    }

    public function SubKriteria1()
    {
        return $this->belongsTo(SubKriteriaTahap1::class, 'id_sk1');
    }
}
