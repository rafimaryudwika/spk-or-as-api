<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PenilaianTahap2 extends Model
{
    use Cachable;

    //
    protected $table = 'nilai_t2';
    protected $fillable = ['nim', 'id_sk2', 'nilai'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap2()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim', 'nim');
    }

    public function SubKriteriaTahap2()
    {
        return $this->belongsTo(SubKriteriaTahap1::class, 'id_sk2', 'id_sk2');
    }
}
