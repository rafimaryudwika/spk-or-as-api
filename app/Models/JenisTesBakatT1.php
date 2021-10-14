<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTesBakatT1 extends Model
{
    //
    protected $table ='jenis_tesbakat';
    protected $primarykey = 'id_tb';

    public function PesertaTahap1()
    {
        return $this->hasMany(PesertaTahap1::class);
    }
}
