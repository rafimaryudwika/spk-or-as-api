<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PesertaTahap2 extends Model
{
    use Cachable;
    //
    protected $table = "peserta_t2";
    protected $primaryKey = 'nim';
    protected $fillable = ['nim', 'lulus'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function PesertaTahap1() //prev
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim');
    }
    public function PesertaTahap3() //next
    {
        return $this->hasOne(PenilaianTahap1::class, 'nim');
    }

    public function PenilaianTahap2()
    {
        return $this->hasMany(PenilaianTahap2::class);
    }

    public function InfoPesertaT2()
    {
        return $this->hasMany(InfoPesertaT2::class);
    }
}
