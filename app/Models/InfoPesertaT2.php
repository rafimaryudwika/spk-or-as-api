<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoPesertaT2 extends Model
{
    use Cachable;

    //
    protected $table = 'info_peserta_t2';
    protected $fillable = ['nim', 'tipe_info2', 'informasi'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap2()
    {
        return $this->belongsTo(PesertaTahap2::class, 'nim', 'nim');
    }

    public function detailInfoT2()
    {
        return $this->belongsTo(DetailInfoT2::class, 'tipe_info2', 'tipe_info2');
    }
}
