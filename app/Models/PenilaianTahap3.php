<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianTahap3 extends Model
{
    use Cachable;

    //
    protected $table = 'nilai_t3';
    protected $fillable = ['nim', 'id_sk3', 'nilai'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap3()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim', 'nim');
    }

    public function SubKriteriaTahap3()
    {
        return $this->belongsTo(SubKriteriaTahap1::class, 'id_sk3', 'id_sk3');
    }
}
