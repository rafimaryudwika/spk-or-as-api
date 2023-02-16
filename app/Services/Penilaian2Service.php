<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap2;
use App\Models\SubKriteriaTahap2;
use App\Repositories\Kriteria2Repository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Penilaian2Repository;
use App\Repositories\SubKriteria2Repository;

class Penilaian2Service
{
    use ResponseAPI;
    protected $subkrit2;
    protected $kriteria2;
    protected $penilaian2;
    protected $request;
    public function __construct(Kriteria2Repository $kriteria2, SubKriteria2Repository $subkrit2, Penilaian2Repository $penilaian2, Request $request)
    {
        $this->kriteria2 = $kriteria2;
        $this->subkrit2 = $subkrit2;
        $this->penilaian2 = $penilaian2;
        $this->request = $request;
    }

    public function dataManipulation()
    {
        // TODO: Refactoring kodingan manipulasi data
        //! Kodingannya sangat tidak rapi!

        $pendaftar = $this->penilaian2->getDataPeserta();
        $kriteria = $this->penilaian2->getSubKriteria();

        $bobot = $kriteria->map(function ($query) {
            $name = $query->k_sc;
            $query->{$name} = $query->bobot;
            if ($query->SubKriteriaTahap2->count() > 1) {
                $query->sub_kriteria = $query->SubKriteriaTahap2
                    ->groupBy('SubKriteriaTahap2.sk_sc')->map(function ($query) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->sk_sc => $sub->bobot];
                        });
                    });
            }
            return $query->only($query->k_sc, 'sub_kriteria');
        });
        $nilai = $pendaftar->filter(function ($query) {
            return $query->PenilaianTahap2->isNotEmpty();
        })->values()->map(function ($query) {
            $test['nama_panggilan'] = $query->panggilan;
            $test['e-mail'] = $query->email;
            $test['nomor_hp'] = $query->no_hp;
            $test['gender'] = $query->gender->gender;
            $test['tempat_lahir'] = $query->tempat_lahir;
            $test['tanggal_lahir'] = $query->tgl_lahir;
            $test['fakultas'] = $query->fakultas->fakultas;
            $test['jurusan'] = $query->jurusan->jurusan;
            $test['bidang_fakultas'] = $query->Fakultas->BidangFakultas->bidang_fak;
            $test['alamat_di_padang'] = $query->alamat_pdg;
            $query->detail = $test;
            $query->lulus = $query->PesertaTahap2->lulus;
            $query->nilai = $query->PenilaianTahap2
                ->groupBy(['SubKriteriaTahap2.KriteriaTahap2.k_sc'])
                ->map(function ($query) {
                    if ($query->count() > 1) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->SubKriteriaTahap2->sk_sc => $sub->nilai];
                        });
                    } else {
                        return $query->pluck('nilai')->first();
                    }
                });
            return $query->only('nim', 'nama', 'nilai', 'detail', 'lulus');
        });

        $max = $pendaftar->pluck('PenilaianTahap2')
            ->flatten()->groupBy(['SubKriteriaTahap2.KriteriaTahap2.k_sc', 'SubKriteriaTahap2.sk_sc'])
            ->map(function ($query) {
                if ($query->count() > 1) {
                    return $query->map(function ($sub) {
                        return $sub->max('nilai');
                    });
                } else {
                    foreach ($query as $qk => $qv) {
                        return $qv->max('nilai');
                    }
                }
            });

        $norm = $nilai->map(function ($item) use ($max) {
            foreach ($item['nilai'] as $k => $v) {
                if (is_object($v) == true) {
                    foreach ($v as $k2 => $v2) {
                        $item['normalisasi'][$k][$k2] = $v2 / $max[$k][$k2];
                    }
                } else {
                    $item['normalisasi'][$k] = $v / $max[$k];
                }
            }
            return $item;
        }, $nilai);

        $sub_total = $norm->map(function ($item) use ($bobot) {
            foreach ($item['normalisasi'] as $k => $v) {
                $totalTemp = 0;
                if (is_array($v) == true) {
                    foreach ($v as $k2 => $v2) {
                        foreach ($bobot as $vSub) {
                            if (isset($vSub[$k])) {
                                $totalTemp += $v2 * $vSub['sub_kriteria'][""][$k2];
                                $item['normalisasi'][$k]['total'] = $totalTemp;
                                break;
                            }
                        }
                    }
                } else {
                    $item['normalisasi'][$k] = $v;
                }
            }
            return $item;
        }, $nilai);

        foreach ($sub_total as $stk => $stv) {
            foreach ($stv['normalisasi'] as $nk => $nv) {
                if (is_array($nv) == true) {
                    $test[$nk] = $nv['total'];
                } else {
                    $test[$nk] = $nv;
                }
            }
            $max_krit[$stk] = $test;
        }
        $max_k = [];
        foreach ($max_krit as $keys => $values) {
            foreach ($values as $keys2 => $data) {
                $max_k[$keys2] = max(array_column($max_krit, $keys2));
            }
        }

        $norm_k = $sub_total->map(function ($item) use ($max_k) {
            foreach ($item['normalisasi'] as $k => $v) {
                if (is_array($v) == true) {
                    foreach ($v as $k2 => $v2) {
                        $item['new_norm'][$k] = $v2 / $max_k[$k];
                    }
                } else {
                    $item['new_norm'][$k] = $v / $max_k[$k];
                }
            }
            return $item;
        });

        $total_k = $norm_k->map(function ($item) use ($bobot) {
            $totalTemp = 0;
            foreach ($item['new_norm'] as $k => $v) {
                foreach ($bobot as $vSub) {
                    if (isset($vSub[$k])) {
                        $totalTemp += $v * $vSub[$k];
                        $item['total'] = $totalTemp;
                    }
                }
            }
            return $item;
        }, $nilai);

        return $total_k;
    }
    public function getAllData()
    {
        try {
            $array['kriteria'] = $this->kriteria2->getAllData();
            $array['subkriteria'] = $this->subkrit2->getAllData();
            $array['subkriteriatranspose'] = $this->subkrit2->transposedData();
            $array['penilaian'] = $this->dataManipulation();
            $array['fakultas'] =  $this->penilaian2->getFakultas();
            return $this->success("Data peserta tahap 2 OR XI", $array);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataByNim($nim)
    {
        try {
            $manipulation = $this->dataManipulation()->where('nim', $nim)->first();
            return $this->success("Data salah peserta tahap 2 OR XI", $manipulation);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $nim = null)
    {
        try {
            $kriteria = KriteriaTahap2::get();
            foreach ($kriteria as $k) {
                $sub_k['kriteria_' . $k->id_k2] = SubKriteriaTahap2::where('id_k2', $k->id_k2)->get('id_sk2');
                $multi_sub = SubKriteriaTahap2::with('KriteriaTahap2')->where('id_k2', $k->id_k2)->get();
                if (!$nim) $valid['nim'] = ['required'];
                foreach ($multi_sub as $sk) {
                    if (count($sub_k['kriteria_' . $k->id_k2]) > 1) {
                        $valid[$k->k_sc . '-' . $sk->sk_sc] = ['required', 'numeric', 'min:0'];
                    } elseif (count($sub_k['kriteria_' . $k->id_k2]) == 1) {
                        $valid[$k->k_sc] = ['required', 'numeric', 'min:0'];
                    }
                }
            }
            $validator = Validator::make($request->all(), $valid);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            //input data-data penilaian
            $result = $this->penilaian2->requestData($request, $nim);

            //mencari data penilaian dari NIM yang sudah dicreate atau berdasarkan NIM saat update
            $total = !$nim
                ? $this->dataManipulation()->where('nim', $request->nim)->first()
                : $this->dataManipulation()->where('nim', $nim)->first();

            //logika kelulusan terjadi saat create dan update dan saat total nilainya diatas 0.5 dan tidak lulus
            if ($total['total'] >= 0.5 && $total['lulus'] === 0) {
                if (!$nim) {
                    $this->autoLulus(1, $request->nim);
                } else {
                    $this->autoLulus(1, $nim);
                }
            }

            //logika ketidaklulusan hanya terjadi saat update nilai dan total nilainya dibawah 0.5
            if ($nim) {
                if ($total['total'] < 0.5 && $total['lulus'] === 1) {
                    $this->autoLulus(0, $nim);
                }
            }

            return $this->success(
                $nim ? "Data penilaian berhasil diupdate!" : "Data penilaian berhasil disubmit!",
                $result,
                $nim ? 200 : 201
            );
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error("Request data gagal!" . $e->getMessage(), $e->getCode());
        }
    }

    public function lulus(Request $request, $nim)
    {
        try {
            $manipulation = $this->dataManipulation()->where('nim', $nim)->first();

            //jika peserta lulus setelah penilaian, maka keputusan kelulusan tidak dapat diganggu gugat
            if ($manipulation['total'] >= 0.5) return $this->error("Keputusan tidak dapat diganggu gugat!", 422);

            $validator = Validator::make($request->all(), [
                'lulus' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $lulus = $this->penilaian2->lulus($validator, $nim);
            return $this->success("Update kelulusan success", $lulus);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error("Update kelulusan failed" . $e->getMessage(), $e->getCode());
        }
    }

    public function autoLulus($params, $nim)
    {
        $manipulation = $this->dataManipulation()->where('nim', $nim)->first();

        if ($manipulation['total'] >= 50) return $this->error("Keputusan tidak dapat diganggu gugat!", 422);

        $data['lulus'] = $params;

        return $this->penilaian2->lulus($data, $nim);
    }
    public function import()
    {
        try {
            $import = $this->penilaian2->import();
            return $this->success('Import peserta dari pendaftar berhasil', $import, 200);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
