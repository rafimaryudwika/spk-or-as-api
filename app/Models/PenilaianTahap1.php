<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianTahap1 extends Model
{
    //
    protected $table = "nilai_t1";
    protected $fillable = ['nim', 'id_sk1', 'nilai'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function PesertaTahap1()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim');
    }

    public function SubKriteria1()
    {
        return $this->belongsTo(SubKriteriaTahap1::class, 'id_sk1');
    }
}
