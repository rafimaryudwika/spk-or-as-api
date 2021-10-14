<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaTahap1 extends Model
{
    //
    protected $table = 'kriteria_t1';
    protected $primarykey = 'id_k1';

    public function SubKriteria1()
    {
        return $this->hasMany(SubKriteriaTahap1::class);
    }
}
