<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = "fakultas";

    public function BidangFakultas()
    {
    return $this->belongsTo(BidangFakultas::class, 'id_bf');
    }
    public function Jurusan()
    {
        return $this->hasMany(Jurusan::class);
    }

}
