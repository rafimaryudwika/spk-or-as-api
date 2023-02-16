<?php

namespace App\Repositories;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\SubKriteriaTahap1;

class SubKriteria1Repository
{
    use ResponseAPI;

    protected $subKriteria;

    public function __construct(SubKriteriaTahap1 $subKriteria)
    {
        $this->subKriteria = $subKriteria;
    }

    public function getAllData()
    {
        $kriteria_new = KriteriaTahap1::with('SubKriteriaTahap1')->get();
        $data = $kriteria_new->map(function ($item) {
            foreach ($item->SubKriteriaTahap1 as $skk => $skv) {
                if ($item->SubKriteriaTahap1->count() > 1) {
                    $subk[$skk]['id_sk1'] = $skv->id_sk1;
                    $subk[$skk]['kode'] = $skv->kode;
                    $subk[$skk]['sub_kriteria'] = $skv->sub_kriteria;
                    $subk[$skk]['sk_sc'] = $skv->sk_sc;
                    $subk[$skk]['bobot'] = $skv->bobot;
                    $item->subkriteria = $subk;
                } elseif ($item->SubKriteriaTahap1->count() == 1) {
                    $item->id_sk1 = $skv->id_sk1;
                    $item->bobot_sk = $skv->bobot;
                }
            }
            return $item->makeHidden('SubKriteriaTahap1', 'created_at', 'updated_at');
        });

        return $data;
    }

    public function transposedData()
    {
        $sub_k = SubKriteriaTahap1::with('KriteriaTahap1')->get();
        $data = $sub_k->map(function ($item) {
            $item->id_sk1 = $item->id_sk1;
            $item->kriteria = $item->KriteriaTahap1->kriteria;
            $item->k_sc = $item->KriteriaTahap1->k_sc;
            return $item->makeHidden('KriteriaTahap1');
        });
        return $data;
    }

    public function getDataById($id)
    {
        $kriteria = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_sk1', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap1->kriteria;
            $data['subkriteria'] = $kriteria->sub_kriteria;
            $data['kode'] = $kriteria->kode;
            $data['bobot'] = $kriteria->bobot;
        }

        return $data;
    }

    public function requestData(Request $request, $id = null)
    {
        if (!$id) {
            $detect = SubKriteriaTahap1::where('id_k1', '=', $request->id_k1)->orderBy('id_sk1', 'desc')->first();
            $a = 1;
            if ($detect == null) {
                $subkriteria =  SubKriteriaTahap1::create([
                    'id_sk1' => (int) $request->id_k1 . $a,
                    'id_k1' => $request->id_k1,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            } else {
                $detect2 = SubKriteriaTahap1::where('id_k1', '=', $request->id_k1)->orderBy('id_sk1', 'desc')->first()->id_sk1;
                $num = $detect2 + $a;
                $subkriteria =  SubKriteriaTahap1::create([
                    'id_sk1' => $num,
                    'id_k1' => $request->id_k1,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            }
        } else {
            $subkriteria = SubKriteriaTahap1::where('id_sk1', '=', $id)->firstOrFail();

            $subkriteria->update([
                'sub_kriteria' => $request->sub_kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->sub_kriteria),
                'bobot' => $request->bobot,
            ]);
        }
        return $subkriteria;
    }

    public function delete($id)
    {
        $kriteria = SubKriteriaTahap1::findOrFail($id);
        $detect = PenilaianTahap1::where('id_sk1', '=', $id)->count();
        if ($detect >= 1) {
            throw new Exception('Subkriteria tidak bisa dihapus karena sedang proses penilaian', 422);
        } else if ($detect == 0) {
            return $kriteria->delete();
        }
    }
}
