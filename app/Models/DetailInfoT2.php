<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailInfoT2 extends Model
{
    use Cachable;

    protected $table = 'detail_info_t2';
    protected $primaryKey = 'tipe_info2';
    protected $fillable = ['tipe_info2', 'nama_info', 'info_sc'];

    public function InfoPesertaT2()
    {
        return $this->hasMany(InfoPesertaT2::class, 'tipe_info2');
    }
}
