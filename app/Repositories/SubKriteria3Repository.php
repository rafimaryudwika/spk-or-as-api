<?php

namespace App\Repositories;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KriteriaTahap3;
use App\Models\PenilaianTahap3;
use App\Models\SubKriteriaTahap3;

class SubKriteria3Repository
{
    use ResponseAPI;

    protected $subKriteria;

    public function __construct(SubKriteriaTahap3 $subKriteria)
    {
        $this->subKriteria = $subKriteria;
    }

    public function getAllData()
    {
        $kriteria_new = KriteriaTahap3::with('SubKriteriaTahap3')->get();
        $data = $kriteria_new->map(function ($item) {
            foreach ($item->SubKriteriaTahap3 as $skk => $skv) {
                if ($item->SubKriteriaTahap3->count() > 1) {
                    $subk[$skk]['id_sk3'] = $skv->id_sk3;
                    $subk[$skk]['kode'] = $skv->kode;
                    $subk[$skk]['sub_kriteria'] = $skv->sub_kriteria;
                    $subk[$skk]['sk_sc'] = $skv->sk_sc;
                    $subk[$skk]['bobot'] = $skv->bobot;
                    $item->subkriteria = $subk;
                } elseif ($item->SubKriteriaTahap3->count() == 1) {
                    $item->id_sk3 = $skv->id_sk3;
                    $item->bobot_sk = $skv->bobot;
                }
            }
            return $item->makeHidden('SubKriteriaTahap3', 'created_at', 'updated_at');
        });

        return $data;
    }

    public function transposedData()
    {
        $sub_k = SubKriteriaTahap3::with('KriteriaTahap3')->get();
        $data = $sub_k->map(function ($item) {
            $item->id_sk3 = $item->id_sk3;
            $item->kriteria = $item->KriteriaTahap3->kriteria;
            $item->k_sc = $item->KriteriaTahap3->k_sc;
            return $item->makeHidden('KriteriaTahap3');
        });
        return $data;
    }

    public function getDataById($id)
    {
        $kriteria = SubKriteriaTahap3::with('KriteriaTahap3')->where('id_sk3', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap3->kriteria;
            $data['subkriteria'] = $kriteria->sub_kriteria;
            $data['kode'] = $kriteria->kode;
            $data['bobot'] = $kriteria->bobot;
        }

        return $data;
    }

    public function requestData(Request $request, $id = null)
    {
        if (!$id) {
            $detect = SubKriteriaTahap3::where('id_k3', '=', $request->id_k3)->orderBy('id_sk3', 'desc')->first();
            $a = 1;
            if ($detect == null) {
                $subkriteria =  SubKriteriaTahap3::create([
                    'id_sk3' => (int) $request->id_k3 . $a,
                    'id_k3' => $request->id_k3,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            } else {
                $detect2 = SubKriteriaTahap3::where('id_k3', '=', $request->id_k3)->orderBy('id_sk3', 'desc')->first()->id_sk3;
                $num = $detect2 + $a;
                $subkriteria =  SubKriteriaTahap3::create([
                    'id_sk3' => $num,
                    'id_k3' => $request->id_k3,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            }
        } else {
            $subkriteria = SubKriteriaTahap3::where('id_sk3', '=', $id)->firstOrFail();

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
        $kriteria = SubKriteriaTahap3::findOrFail($id);
        $detect = PenilaianTahap3::where('id_sk3', '=', $id)->count();
        if ($detect >= 1) {
            throw new Exception('Subkriteria tidak bisa dihapus karena sedang proses penilaian', 422);
        } else if ($detect == 0) {
            return $kriteria->delete();
        }
    }
}
