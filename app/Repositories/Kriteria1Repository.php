<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Str;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\SubKriteriaTahap1;
use App\Traits\ResponseAPI;

class Kriteria1Repository
{
    use ResponseAPI;
    protected $kriteria1;

    public function __construct(KriteriaTahap1 $krit)
    {
        $this->kriteria1 = $krit;
    }

    public function getAllData()
    {
        $kriteria = KriteriaTahap1::get([
            'kriteria_t1.id_k1',
            'kriteria_t1.kode',
            'kriteria_t1.kriteria',
            'kriteria_t1.k_sc',
            'kriteria_t1.bobot'
        ]);

        return $kriteria;
    }

    public function getDataById($id)
    {
        return $this->getAllData()->where('id_k1', $id)->first();
    }

    public function requestData($request, $id = null)
    {
        if (!$id) {
            $num = KriteriaTahap1::orderBy('id_k1', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->id_k1 + $a;
            }
            $kriteria =  KriteriaTahap1::create([
                'id_k1' => $b,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap1::latest()->first()->id_k1;
            $subk_default = SubKriteriaTahap1::create([
                'id_k1' => $latest,
                'id_sk1' => $latest . $a,
                'sub_kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            return $kriteria . $subk_default;
        } else {
            $kriteria = KriteriaTahap1::findOrFail($id);
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
        $kriteria = KriteriaTahap1::findOrFail($id);

        try {
            $detect = SubKriteriaTahap1::where('id_k1', $id)->count();
            $detectSubKrit = SubKriteriaTahap1::where('id_k1', $id)->pluck('id_sk1')->first();
            $detectPeserta = PenilaianTahap1::where('id_sk1', '=', $detectSubKrit)->count();
            if ($detect > 1) {
                throw new Exception('Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu', 422);
            } elseif ($detectPeserta >= 1) {
                throw new Exception('Kriteria gagal dihapus karena salah satu sub-kriteria sudah dipakai untuk penilaian', 422);
            } else {
                $detect2 = SubKriteriaTahap1::where('id_k1', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap1::where('id_k1', $id)->delete();
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
