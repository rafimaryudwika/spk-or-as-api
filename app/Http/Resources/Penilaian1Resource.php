<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\PesertaTahap1;
use App\Models\SubKriteriaTahap1;

class Penilaian1Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $kriteria = KriteriaTahap1::pluck('kriteria')->toArray();
        $subkriteria = SubKriteriaTahap1::pluck('sub_kriteria')->toArray();

        foreach ($subkriteria as $sk) {
            $arr['kriteria'] = $this->kriteria;
        }

        return [
            'nim' => $this->nim,
            'nama' => $this->nama,
            'penilaian' => [
                'kriteria' => $this->kriteria,
                'sub' => [
                    'subkriteria' => $this->subkriteria,
                    'nilai' => $this->nilai
                ]
            ]
        ];
    }
}
