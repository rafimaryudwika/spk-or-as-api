<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangFakultas extends Model
{
    protected $table = "bidang_fak";

    public function Fakultas()
    {
        return $this->hasMany(Fakultas::class);
    }
}
