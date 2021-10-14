<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTahap1 extends Model
{
    //
    protected $table="peserta_t1";
    protected $primarykey='nim';

    public function Pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'nim');
    }

    public function PenilaianTahap1()
    {
        return $this->hasMany(PenilaianTahap1::class);
    }

    public function JenisTesBakat()
    {
        return $this->belongsTo(JenisTesBakatT1::class, 'id_tb');
    }

}
