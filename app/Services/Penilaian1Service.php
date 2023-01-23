<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap1;
use App\Models\SubKriteriaTahap1;
use App\Http\Requests\Penilaian1Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Penilaian1Repository;

class Penilaian1Service
{
    use ResponseAPI;
    protected $subkrit1;
    protected $kriteria1;
    protected $penilaian1;
    protected $request;
    public function __construct(KriteriaTahap1 $kriteria1, SubKriteriaTahap1 $subkrit1, Penilaian1Repository $penilaian1, Request $request)
    {
        $this->kriteria1 = $kriteria1;
        $this->subkrit1 = $subkrit1;
        $this->penilaian1 = $penilaian1;
        $this->request = $request;
    }

    public function dataManipulation()
    {
        $pendaftar = $this->penilaian1->getDataPeserta();
        $kriteria = $this->penilaian1->getSubKriteria();

        $bobot = $kriteria->map(function ($query) {
            $name = $query->k_sc;
            $query->{$name} = $query->bobot;
            if ($query->SubKriteriaTahap1->count() > 1) {
                $query->sub_kriteria = $query->SubKriteriaTahap1
                    ->groupBy('SubKriteriaTahap1.sk_sc')->map(function ($query) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->sk_sc => $sub->bobot];
                        });
                    });
            }
            return $query->only($query->k_sc, 'sub_kriteria');
        });
        $nilai = $pendaftar->filter(function ($query) {
            return $query->PenilaianTahap1->isNotEmpty();
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
            $query->lulus = $query->PesertaTahap1->lulus;
            $query->nilai = $query->PenilaianTahap1
                ->groupBy(['SubKriteriaTahap1.KriteriaTahap1.k_sc'])
                ->map(function ($query) {
                    if ($query->count() > 1) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->SubKriteriaTahap1->sk_sc => $sub->nilai];
                        });
                    } else {
                        return $query->pluck('nilai')->first();
                    }
                });
            return $query->only('nim', 'nama', 'nilai', 'detail', 'lulus');
        });

        $max = $pendaftar->pluck('PenilaianTahap1')
            ->flatten()->groupBy(['SubKriteriaTahap1.KriteriaTahap1.k_sc', 'SubKriteriaTahap1.sk_sc'])
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
                        $item['normalisasi'][$k][$k2] = number_format($v2 / $max[$k][$k2], 3);
                    }
                } else {
                    $item['normalisasi'][$k] = number_format($v / $max[$k], 3);
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
                                $item['normalisasi'][$k]['total'] = number_format($totalTemp, 3);
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
                        $item['new_norm'][$k] = number_format($v2 / $max_k[$k], 3);
                    }
                } else {
                    $item['new_norm'][$k] = number_format($v / $max_k[$k], 3);
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
                        $item['total'] = number_format($totalTemp, 3);
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
            $manipulation = $this->dataManipulation();
            return $this->success("Data peserta tahap 1 OR XI", $manipulation);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataByNim($nim)
    {
        try {
            $manipulation = $this->dataManipulation()->where('nim', $nim)->first();
            return $this->success("Data salah peserta tahap 1 OR XI", $manipulation);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $nim = null)
    {
        try {
            $kriteria = KriteriaTahap1::get();
            foreach ($kriteria as $k) {
                $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
                $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                if (!$nim) $valid['nim'] = ['required'];
                foreach ($multi_sub as $sk) {
                    if (count($sub_k['kriteria_' . $k->id_k1]) > 1) {
                        $valid[$k->k_sc . '-' . $sk->sk_sc] = ['required', 'numeric', 'min:0'];
                    } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
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

            // return $request;

            $result = $this->penilaian1->requestData($request, $nim);

            $total = !$nim
                ? $this->dataManipulation()->where('nim', $request->nim)->first()
                : $this->dataManipulation()->where('nim', $nim)->first();

            if ($total['total'] >= 50) {
                if (!$nim) {
                    $this->autoLulus(1, $request->nim);
                } else {
                    $this->autoLulus(1, $nim);
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

            if ($manipulation['total'] >= 50) return $this->error("Keputusan tidak dapat diganggu gugat!", 422);

            $validator = Validator::make($request->all(), [
                'lulus' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $lulus = $this->penilaian1->lulus($validator, $nim);
            return $this->success("Update kelulusan success", $lulus);
        } catch (Exception $e) {
            return $this->error("Update kelulusan failed" . $e->getMessage(), $e->getCode());
        }
    }

    public function autoLulus($params, $nim)
    {
        $manipulation = $this->dataManipulation()->where('nim', $nim)->first();

        if ($manipulation['total'] >= 50) return $this->error("Keputusan tidak dapat diganggu gugat!", 422);

        $data['lulus'] = $params;

        return $this->penilaian1->lulus($data, $nim);
    }
    public function import()
    {
        try {
            $import = $this->penilaian1->import();
            return $this->success('Import peserta dari pendaftar berhasil', $import, 200);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function ratio()
    {
        // function getRatio($array)
        // {
        //     if (count($array) === 2) {
        //         foreach ($array as $k => $v) {
        //             $data[$k] = $v['total'];
        //         }
        //     } else {
        //         $response = ['message' => "Arraynya kurang atau lebih dari 2"];
        //         return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        //     }
        //     $min = min([$data[0], $data[1]]);
        //     $max = max([$data[0], $data[1]]);
        //     $count = fdiv($max, $min);
        //     if ($data[0] > $data[1]) {
        //         $ratio = round($count, 2) . ':1';
        //     } else {
        //         $ratio = '1:' . round($count, 2);
        //     }
        //     $percent = round($count * 100, 2);
        //     return [
        //         'ratio' => "$ratio",
        //         'persentase' => "$percent" . ' %',
        //     ];
        // }
        // $bp = Pendaftar::query()
        //     ->with(['Gender', 'PesertaTahap1', 'PenilaianTahap1.SubKriteriaTahap1.KriteriaTahap1'])
        //     ->whereHas('PesertaTahap1')->whereRelation('PesertaTahap1', 'lulus', '1')
        //     ->select(DB::raw('concat("20", substr(CONVERT(nim, CHAR), 1, 2)) as bp, count(*) as total'))
        //     ->groupBy('bp')
        //     ->get();
        // $gender = Pendaftar::query()
        //     ->with(['Gender', 'PesertaTahap1'])
        //     ->whereHas('PesertaTahap1')->whereRelation('PesertaTahap1', 'lulus', '1')
        //     ->select('id_g', DB::raw('count(*) as total'))
        //     ->groupBy('id_g')
        //     ->get();
        // $bidang_fakultas = BidangFakultas::with(['Fakultas.Pendaftar.PesertaTahap1'])
        //     ->has('Fakultas.Pendaftar.PesertaTahap1', '<>', null)
        //     ->get();
        // return $bidang_fakultas;
    }
}
