<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Fakultas extends Model
{
    use Cachable;

    protected $table = "fakultas";
    protected $primaryKey = 'id_f';

    public function BidangFakultas()
    {
        return $this->belongsTo(BidangFakultas::class, 'id_bf', 'id_bf');
    }
    public function Jurusan()
    {
        return $this->hasMany(Jurusan::class);
    }
}
