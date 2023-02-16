<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Str;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;
use App\Traits\ResponseAPI;

class Kriteria2Repository
{
    use ResponseAPI;
    protected $kriteria2;

    public function __construct(KriteriaTahap2 $krit)
    {
        $this->kriteria2 = $krit;
    }

    public function getAllData()
    {
        $kriteria = KriteriaTahap2::get([
            'kriteria_t2.id_k2',
            'kriteria_t2.kode',
            'kriteria_t2.kriteria',
            'kriteria_t2.k_sc',
            'kriteria_t2.bobot'
        ]);

        return $kriteria;
    }

    public function getDataById($id)
    {
        return $this->getAllData()->where('id_k2', $id)->first();
    }

    public function requestData($request, $id = null)
    {
        if (!$id) {
            $num = KriteriaTahap2::orderBy('id_k2', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->id_k2 + $a;
            }
            $kriteria =  KriteriaTahap2::create([
                'id_k2' => $b,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap2::latest()->first()->id_k2;
            $subk_default = SubKriteriaTahap2::create([
                'id_k2' => $latest,
                'id_sk2' => $latest . $a,
                'sub_kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            return $kriteria . $subk_default;
        } else {
            $kriteria = KriteriaTahap2::findOrFail($id);
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
        $kriteria = KriteriaTahap2::findOrFail($id);

        try {
            $detect = SubKriteriaTahap2::where('id_k2', $id)->count();
            $detectSubKrit = SubKriteriaTahap2::where('id_k2', $id)->pluck('id_sk2')->first();
            $detectPeserta = PenilaianTahap2::where('id_sk2', '=', $detectSubKrit)->count();
            if ($detect > 1) {
                throw new Exception('Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu', 422);
            } elseif ($detectPeserta >= 1) {
                throw new Exception('Kriteria gagal dihapus karena salah satu sub-kriteria sudah dipakai untuk penilaian', 422);
            } else {
                $detect2 = SubKriteriaTahap2::where('id_k2', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap2::where('id_k2', $id)->delete();
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
