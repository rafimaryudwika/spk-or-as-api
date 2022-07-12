<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailInfoT1 extends Model
{
    use Cachable;

    protected $table = 'detail_info_t1';
    protected $primaryKey = 'tipe_info1';
    protected $fillable = ['tipe_info1', 'nama_info', 'info_sc'];
    public $incrementing = false;

    public function InfoPesertaT1()
    {
        return $this->hasMany(InfoPesertaT1::class, 'tipe_info1');
    }
}
