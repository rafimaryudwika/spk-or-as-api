<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Gender extends Model
{
    use Cachable;

    protected $table = 'gender';
    protected $primaryKey = 'id_g';

    public function Pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }
}
