<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaTahap3 extends Model
{
    use Cachable;
    //
    protected $table = "peserta_t3";
    protected $primarykey = 'nim';
    protected $fillable = ['nim', 'lulus'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function PesertaTahap2()
    {
        return $this->belongsTo(PesertaTahap2::class, 'nim');
    }

    public function PenilaianTahap3()
    {
        return $this->hasMany(PenilaianTahap3::class);
    }

    public function InfoPesertaT3()
    {
        return $this->hasMany(InfoPesertaT3::class);
    }
}
