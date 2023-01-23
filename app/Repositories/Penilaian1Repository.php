<?php

namespace App\Repositories;

use Exception;
use App\Models\Pendaftar;
use App\Models\PesertaTahap1;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\SubKriteriaTahap1;

class Penilaian1Repository
{
    protected $pendaftar;
    protected $penilaian1;
    protected $subkrit1;
    protected $kriteria1;
    protected $peserta;

    public function ___construct(Pendaftar $pendaftar, PenilaianTahap1 $penilaian1, KriteriaTahap1 $kriteria1, SubKriteriaTahap1 $subkrit1, PesertaTahap1 $peserta)
    {
        $this->pendaftar = $pendaftar;
        $this->penilaian1 = $penilaian1;
        $this->kriteria1 = $kriteria1;
        $this->subkrit1 = $subkrit1;
        $this->peserta = $peserta;
    }
    public function getDataPeserta()
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap1', 'PenilaianTahap1.SubKriteriaTahap1.KriteriaTahap1'])
            ->whereHas('PesertaTahap1')
            ->get();

        return $pendaftar;
    }
    public function getDataPesertaByNim($nim)
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap1', 'PenilaianTahap1.SubKriteriaTahap1.KriteriaTahap1'])
            ->whereHas('PesertaTahap1')->where('nim', $nim)
            ->get();

        return $pendaftar;
    }

    public function getSubKriteria()
    {
        $kriteria = KriteriaTahap1::query()->with(['SubKriteriaTahap1'])
            ->get();

        return $kriteria;
    }

    public function requestData($data, $nim = null)
    {
        // return $this->penilaian1;
        $kriteria = KriteriaTahap1::all();
        $subkriteria = SubKriteriaTahap1::query()->pluck('id_sk1')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
            $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k1]) > 1) {
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                    $nilai_sk[] = $k->k_sc;
                }
            }
        }

        if (!$nim) {
            foreach (array_combine($subkriteria, $nilai_sk) as $sk => $ns) {
                $bulk_insert[] = [
                    'nim' => $data->nim,
                    'id_sk1' => $sk,
                    'nilai' => $data->$ns
                ];
            }
            $requestData =  PenilaianTahap1::insert($bulk_insert);
        } else {
            foreach ($nilai_sk as $ns) {
                $bulk_update[] = [
                    'nilai' => $data->$ns
                ];
            }
            $requestData = '';
            foreach (array_combine($subkriteria, $bulk_update) as $sk => $bulk) {
                $requestData .= PenilaianTahap1::where('nim', $nim)->where('id_sk1', $sk)
                    ->update($bulk);
            }
        }
        return $requestData;
    }

    public function lulus($data, $id)
    {
        $lulus = PesertaTahap1::where('nim', $id)->firstOrFail();
        $lulus->lulus = $data['lulus'];
        return $lulus->update();
    }

    public function import()
    {
        $read = Pendaftar::where('daftar_ulang', '=', '1')->get();
        foreach ($read as $i => $r) {
            $new[$i]['nim'] = $r->nim;
            $new[$i]['lulus'] = 0;
        }
        $exist = PesertaTahap1::get();
        $exist = $exist->pluck('nim');
        $filter = collect($new)->reject(function ($value) use ($exist) {
            return $exist->contains($value['nim']);
        });
        $filterArray = $filter->toArray();

        if ($filterArray == null) throw new Exception('Tidak ada data yg diimport', 204);

        return PesertaTahap1::insert($filterArray);
    }
}
