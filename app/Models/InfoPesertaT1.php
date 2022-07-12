<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoPesertaT1 extends Model
{
    use Cachable;

    //
    protected $table = 'info_peserta_t1';
    protected $fillable = ['nim', 'tipe_info1', 'informasi'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap1()
    {
        return $this->belongsTo(PesertaTahap1::class, 'nim', 'nim');
    }

    public function detailInfoT1()
    {
        return $this->belongsTo(DetailInfoT1::class, 'tipe_info1', 'tipe_info1');
    }
}
