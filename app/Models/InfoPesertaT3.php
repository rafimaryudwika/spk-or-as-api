<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoPesertaT3 extends Model
{
    use Cachable;

    //
    protected $table = 'info_peserta_t3';
    protected $fillable = ['nim', 'tipe_info3', 'informasi'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = 'id';
    public $timestamps = false;

    public function PesertaTahap3()
    {
        return $this->belongsTo(PesertaTahap3::class, 'nim', 'nim');
    }

    public function detailInfoT3()
    {
        return $this->belongsTo(DetailInfoT3::class, 'tipe_info3', 'tipe_info3');
    }
}
