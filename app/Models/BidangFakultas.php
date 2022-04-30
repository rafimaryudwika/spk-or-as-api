<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BidangFakultas extends Model
{
    use Cachable;

    protected $table = "bidang_fak";
    protected $primaryKey = "id_bf";

    public function Fakultas()
    {
        return $this->hasMany(Fakultas::class);
    }
}
