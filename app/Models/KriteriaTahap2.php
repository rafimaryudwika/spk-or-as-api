<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class KriteriaTahap2 extends Model
{
    use Cachable;

    protected $table = 'kriteria_t2';
    protected $primaryKey = 'id_k2';
    protected $fillable = ['id_k2', 'kriteria', 'bobot'];

    public function SubKriteriaTahap1()
    {
        return $this->hasMany(SubKriteriaTahap2::class, 'id_k2');
    }
}
