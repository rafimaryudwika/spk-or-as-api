<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Fakultas extends Model
{
    // use Cachable;

    // protected $appends = ['total'];
    protected $table = "fakultas";
    protected $primaryKey = 'id_f';

    public function BidangFakultas()
    {
        return $this->belongsTo(BidangFakultas::class, 'id_bf', 'id_bf');
    }
    public function Jurusan()
    {
        return $this->hasMany(Jurusan::class, 'id_f');
    }
    public function Pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'id_f');
    }
    // public function getTotalAttribute()
    // {
    //     return $this->Pendaftar->count();
    // }
}
