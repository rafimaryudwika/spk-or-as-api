<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailInfoT3 extends Model
{
    use Cachable;

    protected $table = 'detail_info_t3';
    protected $primaryKey = 'tipe_info3';
    protected $fillable = ['tipe_info3', 'nama_info', 'info_sc'];

    public function InfoPesertaT2()
    {
        return $this->hasMany(InfoPesertaT3::class, 'tipe_info3');
    }
}
