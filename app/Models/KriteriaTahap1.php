<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class KriteriaTahap1 extends Model
{
    use Cachable;

    protected $table = 'kriteria_t1';
    protected $primaryKey = 'id_k1';

    public function SubKriteria1()
    {
        return $this->hasMany(SubKriteriaTahap1::class);
    }
}
