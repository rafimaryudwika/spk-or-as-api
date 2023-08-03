<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Jurusan extends Model
{
    use Cachable;

    protected $table = "jurusan";
    protected $primaryKey = 'id_j';

    public function Fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_f');
    }
    public function Pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'id_j');
    }
}
