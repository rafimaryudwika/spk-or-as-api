<?php

namespace App\Repositories;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;

class SubKriteria2Repository
{
    use ResponseAPI;

    protected $subKriteria;

    public function __construct(SubKriteriaTahap2 $subKriteria)
    {
        $this->subKriteria = $subKriteria;
    }

    public function getAllData()
    {
        $kriteria_new = KriteriaTahap2::with('SubKriteriaTahap2')->get();
        $data = $kriteria_new->map(function ($item) {
            foreach ($item->SubKriteriaTahap2 as $skk => $skv) {
                if ($item->SubKriteriaTahap2->count() > 1) {
                    $subk[$skk]['id_sk2'] = $skv->id_sk2;
                    $subk[$skk]['kode'] = $skv->kode;
                    $subk[$skk]['sub_kriteria'] = $skv->sub_kriteria;
                    $subk[$skk]['sk_sc'] = $skv->sk_sc;
                    $subk[$skk]['bobot'] = $skv->bobot;
                    $item->subkriteria = $subk;
                } elseif ($item->SubKriteriaTahap1->count() == 1) {
                    $item->id_sk2 = $skv->id_sk2;
                    $item->bobot_sk = $skv->bobot;
                }
            }
            return $item->makeHidden('SubKriteriaTahap2', 'created_at', 'updated_at');
        });

        return $data;
    }

    public function transposedData()
    {
        $sub_k = SubKriteriaTahap2::with('KriteriaTahap2')->get();
        $data = $sub_k->map(function ($item) {
            $item->id_sk2 = $item->id_sk2;
            $item->kriteria = $item->KriteriaTahap2->kriteria;
            $item->k_sc = $item->KriteriaTahap2->k_sc;
            return $item->makeHidden('KriteriaTahap2');
        });
        return $data;
    }

    public function getDataById($id)
    {
        $kriteria = SubKriteriaTahap2::with('KriteriaTahap2')->where('id_sk2', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap2->kriteria;
            $data['subkriteria'] = $kriteria->sub_kriteria;
            $data['kode'] = $kriteria->kode;
            $data['bobot'] = $kriteria->bobot;
        }

        return $data;
    }

    public function requestData(Request $request, $id = null)
    {
        if (!$id) {
            $detect = SubKriteriaTahap2::where('id_k2', '=', $request->id_k2)->orderBy('id_sk2', 'desc')->first();
            $a = 1;
            if ($detect == null) {
                $subkriteria =  SubKriteriaTahap2::create([
                    'id_sk2' => (int) $request->id_k2 . $a,
                    'id_k2' => $request->id_k2,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            } else {
                $detect2 = SubKriteriaTahap2::where('id_k2', '=', $request->id_k2)->orderBy('id_sk2', 'desc')->first()->id_sk2;
                $num = $detect2 + $a;
                $subkriteria =  SubKriteriaTahap2::create([
                    'id_sk2' => $num,
                    'id_k2' => $request->id_k2,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            }
        } else {
            $subkriteria = SubKriteriaTahap2::where('id_sk2', '=', $id)->firstOrFail();

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
        $kriteria = SubKriteriaTahap2::findOrFail($id);
        $detect = PenilaianTahap2::where('id_sk2', '=', $id)->count();
        if ($detect >= 1) {
            throw new Exception('Subkriteria tidak bisa dihapus karena sedang proses penilaian', 422);
        } else if ($detect == 0) {
            return $kriteria->delete();
        }
    }
}
