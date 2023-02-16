<?php

namespace App\Repositories;

use Exception;
use App\Models\Fakultas;
use App\Models\Pendaftar;
use App\Models\PesertaTahap1;
use App\Models\PesertaTahap2;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;

class Penilaian2Repository
{
    protected $pendaftar;
    protected $penilaian2;
    protected $subkrit2;
    protected $kriteria2;
    protected $peserta;

    public function ___construct(Pendaftar $pendaftar, PenilaianTahap2 $penilaian2, KriteriaTahap2 $kriteria2, SubKriteriaTahap2 $subkrit2, PesertaTahap2 $peserta)
    {
        $this->pendaftar = $pendaftar;
        $this->penilaian2 = $penilaian2;
        $this->kriteria2 = $kriteria2;
        $this->subkrit2 = $subkrit2;
        $this->peserta = $peserta;
    }
    public function getDataPeserta()
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap2', 'PenilaianTahap2.SubKriteriaTahap2.KriteriaTahap2'])
            ->whereHas('PesertaTahap2')
            ->get();

        return $pendaftar;
    }

    public function getSubKriteria()
    {
        $kriteria = KriteriaTahap2::query()->with(['SubKriteriaTahap2'])
            ->get();

        return $kriteria;
    }

    public function getFakultas()
    {
        return Fakultas::with('BidangFakultas')->get();
    }

    public function requestData($data, $nim = null)
    {
        $check = !$nim ? PesertaTahap2::where('nim', $data->nim)->firstOrFail() : PesertaTahap2::where('nim', $nim)->firstOrFail();

        if (!$check) throw new Exception('NIM tidak ada di data peserta! Pastikan NIM tersebut terdaftar di data peserta!', 404);

        $kriteria = KriteriaTahap2::all();
        $subkriteria = SubKriteriaTahap2::query()->pluck('id_sk2')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k2] = SubKriteriaTahap2::where('id_k2', $k->id_k2)->get('id_sk2');
            $multi_sub = SubKriteriaTahap2::with('KriteriaTahap2')->where('id_k2', $k->id_k2)->get();
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k2]) > 1) {
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k2]) == 1) {
                    $nilai_sk[] = $k->k_sc;
                }
            }
        }

        if (!$nim) {
            foreach (array_combine($subkriteria, $nilai_sk) as $sk => $ns) {
                $bulk_insert[] = [
                    'nim' => $data->nim,
                    'id_sk2' => $sk,
                    'nilai' => $data->$ns
                ];
            }
            $requestData =  PenilaianTahap2::insert($bulk_insert);
        } else {
            foreach ($nilai_sk as $ns) {
                $bulk_update[] = [
                    'nilai' => $data->$ns
                ];
            }
            $requestData = '';
            foreach (array_combine($subkriteria, $bulk_update) as $sk => $bulk) {
                $requestData .= PenilaianTahap2::where('nim', $nim)->where('id_sk2', $sk)
                    ->update($bulk);
            }
        }
        return $requestData;
    }

    public function lulus($data, $id)
    {
        $lulus = PesertaTahap2::where('nim', $id)->firstOrFail();
        $lulus->lulus = $data['lulus'];
        return $lulus->update();
    }

    public function import()
    {
        $read = PesertaTahap1::where('lulus', '=', '1')->get();
        foreach ($read as $i => $r) {
            $new[$i]['nim'] = $r->nim;
            $new[$i]['lulus'] = 0;
        }
        $exist = PesertaTahap2::get();
        $exist = $exist->pluck('nim');
        $filter = collect($new)->reject(function ($value) use ($exist) {
            return $exist->contains($value['nim']);
        });
        $filterArray = $filter->toArray();

        if ($filterArray == null) throw new Exception('Tidak ada data yg diimport', 204);

        return PesertaTahap2::insert($filterArray);
    }
}
