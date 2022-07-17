<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class KriteriaTahap3 extends Model
{
    use Cachable;

    protected $table = 'kriteria_t3';
    protected $primaryKey = 'id_k3';
    protected $fillable = ['id_k3', 'kriteria', 'k_sc', 'kode', 'bobot'];

    public function SubKriteriaTahap3()
    {
        return $this->hasMany(SubKriteriaTahap3::class, 'id_k3');
    }
}
