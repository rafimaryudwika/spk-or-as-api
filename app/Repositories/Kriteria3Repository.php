<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Str;
use App\Models\KriteriaTahap3;
use App\Models\PenilaianTahap3;
use App\Models\SubKriteriaTahap3;
use App\Traits\ResponseAPI;

class Kriteria3Repository
{
    use ResponseAPI;
    protected $kriteria3;

    public function __construct(KriteriaTahap3 $krit)
    {
        $this->kriteria3 = $krit;
    }

    public function getAllData()
    {
        $kriteria = KriteriaTahap3::get([
            'kriteria_t3.id_k3',
            'kriteria_t3.kode',
            'kriteria_t3.kriteria',
            'kriteria_t3.k_sc',
            'kriteria_t3.bobot'
        ]);

        return $kriteria;
    }

    public function getDataById($id)
    {
        return $this->getAllData()->where('id_k3', $id)->first();
    }

    public function requestData($request, $id = null)
    {
        if (!$id) {
            $num = KriteriaTahap3::orderBy('id_k3', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->id_k3 + $a;
            }
            $kriteria =  KriteriaTahap3::create([
                'id_k3' => $b,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap3::latest()->first()->id_k3;
            $subk_default = SubKriteriaTahap3::create([
                'id_k3' => $latest,
                'id_sk3' => $latest . $a,
                'sub_kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            return $kriteria . $subk_default;
        } else {
            $kriteria = KriteriaTahap3::findOrFail($id);
            $kriteria->update([
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot,
            ]);
            return $kriteria;
        }
    }

    public function deleteData($id)
    {
        $kriteria = KriteriaTahap3::findOrFail($id);

        try {
            $detect = SubKriteriaTahap3::where('id_k3', $id)->count();
            $detectSubKrit = SubKriteriaTahap3::where('id_k3', $id)->pluck('id_sk3')->first();
            $detectPeserta = PenilaianTahap3::where('id_sk3', '=', $detectSubKrit)->count();
            if ($detect > 1) {
                throw new Exception('Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu', 422);
            } elseif ($detectPeserta >= 1) {
                throw new Exception('Kriteria gagal dihapus karena salah satu sub-kriteria sudah dipakai untuk penilaian', 422);
            } else {
                $detect2 = SubKriteriaTahap3::where('id_k3', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap3::where('id_k3', $id)->delete();
                    $kriteria->delete();
                    return $this->success('Kriteria and its subkriteria deleted', $subkriteria . ' , ' . $kriteria, 200);
                } else {
                    $kriteria->delete();
                    return $this->success('Kriteria deleted', $kriteria, 200);
                }
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
