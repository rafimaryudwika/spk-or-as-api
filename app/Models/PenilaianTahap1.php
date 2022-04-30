<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PenilaianTahap1 extends Model
{
    use Cachable;

    //
    protected $table = 'nilai_t1';
    protected $fillable = ['nim', 'id_sk1', 'nilai'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap1()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim', 'nim');
    }

    public function SubKriteriaTahap1()
    {
        return $this->belongsTo(SubKriteriaTahap1::class, 'id_sk1', 'id_sk1');
    }
}
