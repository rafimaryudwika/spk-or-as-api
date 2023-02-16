<?php

namespace App\Repositories;

use Exception;
use App\Models\Fakultas;
use App\Models\Pendaftar;
use App\Models\PesertaTahap2;
use App\Models\PesertaTahap3;
use App\Models\KriteriaTahap3;
use App\Models\PenilaianTahap3;
use App\Models\SubKriteriaTahap3;

class Penilaian3Repository
{
    protected $pendaftar;
    protected $penilaian3;
    protected $subkrit3;
    protected $kriteria3;
    protected $peserta;

    public function ___construct(Pendaftar $pendaftar, PenilaianTahap3 $penilaian3, KriteriaTahap3 $kriteria3, SubKriteriaTahap3 $subkrit3, PesertaTahap3 $peserta)
    {
        $this->pendaftar = $pendaftar;
        $this->penilaian3 = $penilaian3;
        $this->kriteria3 = $kriteria3;
        $this->subkrit3 = $subkrit3;
        $this->peserta = $peserta;
    }
    public function getDataPeserta()
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap3', 'PenilaianTahap3.SubKriteriaTahap3.KriteriaTahap3'])
            ->whereHas('PesertaTahap3')
            ->get();

        return $pendaftar;
    }

    public function getSubKriteria()
    {
        $kriteria = KriteriaTahap3::query()->with(['SubKriteriaTahap3'])
            ->get();

        return $kriteria;
    }

    public function getFakultas()
    {
        return Fakultas::with('BidangFakultas')->get();
    }

    public function requestData($data, $nim = null)
    {
        $check = !$nim ? PesertaTahap3::where('nim', $data->nim)->firstOrFail() : PesertaTahap3::where('nim', $nim)->firstOrFail();

        if (!$check) throw new Exception('NIM tidak ada di data peserta! Pastikan NIM tersebut terdaftar di data peserta!', 404);

        $kriteria = KriteriaTahap3::all();
        $subkriteria = SubKriteriaTahap3::query()->pluck('id_sk3')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k3] = SubKriteriaTahap3::where('id_k3', $k->id_k3)->get('id_sk3');
            $multi_sub = SubKriteriaTahap3::with('KriteriaTahap3')->where('id_k3', $k->id_k3)->get();
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k3]) > 1) {
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k3]) == 1) {
                    $nilai_sk[] = $k->k_sc;
                }
            }
        }

        if (!$nim) {
            foreach (array_combine($subkriteria, $nilai_sk) as $sk => $ns) {
                $bulk_insert[] = [
                    'nim' => $data->nim,
                    'id_sk3' => $sk,
                    'nilai' => $data->$ns
                ];
            }
            $requestData =  PenilaianTahap3::insert($bulk_insert);
        } else {
            foreach ($nilai_sk as $ns) {
                $bulk_update[] = [
                    'nilai' => $data->$ns
                ];
            }
            $requestData = '';
            foreach (array_combine($subkriteria, $bulk_update) as $sk => $bulk) {
                $requestData .= PenilaianTahap3::where('nim', $nim)->where('id_sk3', $sk)
                    ->update($bulk);
            }
        }
        return $requestData;
    }

    public function lulus($data, $id)
    {
        $lulus = PesertaTahap3::where('nim', $id)->firstOrFail();
        $lulus->lulus = $data['lulus'];
        return $lulus->update();
    }

    public function import()
    {
        $read = PesertaTahap2::where('lulus', '=', '1')->get();
        foreach ($read as $i => $r) {
            $new[$i]['nim'] = $r->nim;
            $new[$i]['lulus'] = 0;
        }
        $exist = PesertaTahap3::get();
        $exist = $exist->pluck('nim');
        $filter = collect($new)->reject(function ($value) use ($exist) {
            return $exist->contains($value['nim']);
        });
        $filterArray = $filter->toArray();

        if ($filterArray == null) throw new Exception('Tidak ada data yg diimport', 204);

        return PesertaTahap3::insert($filterArray);
    }
}
