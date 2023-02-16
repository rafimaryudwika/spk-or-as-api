<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap3;
use App\Models\SubKriteriaTahap3;
use App\Repositories\Kriteria3Repository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Penilaian3Repository;
use App\Repositories\SubKriteria3Repository;

class Penilaian3Service
{
    use ResponseAPI;
    protected $subkrit3;
    protected $kriteria3;
    protected $penilaian3;
    protected $request;
    public function __construct(Kriteria3Repository $kriteria3, SubKriteria3Repository $subkrit3, Penilaian3Repository $penilaian3, Request $request)
    {
        $this->kriteria3 = $kriteria3;
        $this->subkrit3 = $subkrit3;
        $this->penilaian3 = $penilaian3;
        $this->request = $request;
    }

    public function dataManipulation()
    {
        // TODO: Refactoring kodingan manipulasi data
        //! Kodingannya sangat tidak rapi!

        $pendaftar = $this->penilaian3->getDataPeserta();
        $kriteria = $this->penilaian3->getSubKriteria();

        $bobot = $kriteria->map(function ($query) {
            $name = $query->k_sc;
            $query->{$name} = $query->bobot;
            if ($query->SubKriteriaTahap3->count() > 1) {
                $query->sub_kriteria = $query->SubKriteriaTahap3
                    ->groupBy('SubKriteriaTahap3.sk_sc')->map(function ($query) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->sk_sc => $sub->bobot];
                        });
                    });
            }
            return $query->only($query->k_sc, 'sub_kriteria');
        });
        $nilai = $pendaftar->filter(function ($query) {
            return $query->PenilaianTahap3->isNotEmpty();
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
            $query->lulus = $query->PesertaTahap3->lulus;
            $query->nilai = $query->PenilaianTahap3
                ->groupBy(['SubKriteriaTahap3.KriteriaTahap3.k_sc'])
                ->map(function ($query) {
                    if ($query->count() > 1) {
                        return $query->mapWithKeys(function ($sub) {
                            return [$sub->SubKriteriaTahap3->sk_sc => $sub->nilai];
                        });
                    } else {
                        return $query->pluck('nilai')->first();
                    }
                });
            return $query->only('nim', 'nama', 'nilai', 'detail', 'lulus');
        });

        $max = $pendaftar->pluck('PenilaianTahap3')
            ->flatten()->groupBy(['SubKriteriaTahap3.KriteriaTahap3.k_sc', 'SubKriteriaTahap3.sk_sc'])
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
            $array['kriteria'] = $this->kriteria3->getAllData();
            $array['subkriteria'] = $this->subkrit3->getAllData();
            $array['subkriteriatranspose'] = $this->subkrit3->transposedData();
            $array['penilaian'] = $this->dataManipulation();
            $array['fakultas'] =  $this->penilaian3->getFakultas();
            return $this->success("Data peserta tahap 3 OR XI", $array);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataByNim($nim)
    {
        try {
            $manipulation = $this->dataManipulation()->where('nim', $nim)->first();
            return $this->success("Data salah peserta tahap 3 OR XI", $manipulation);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $nim = null)
    {
        try {
            $kriteria = KriteriaTahap3::get();
            foreach ($kriteria as $k) {
                $sub_k['kriteria_' . $k->id_k3] = SubKriteriaTahap3::where('id_k3', $k->id_k3)->get('id_sk3');
                $multi_sub = SubKriteriaTahap3::with('KriteriaTahap3')->where('id_k3', $k->id_k3)->get();
                if (!$nim) $valid['nim'] = ['required'];
                foreach ($multi_sub as $sk) {
                    if (count($sub_k['kriteria_' . $k->id_k3]) > 1) {
                        $valid[$k->k_sc . '-' . $sk->sk_sc] = ['required', 'numeric', 'min:0'];
                    } elseif (count($sub_k['kriteria_' . $k->id_k3]) == 1) {
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
            $result = $this->penilaian3->requestData($request, $nim);

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
            $lulus = $this->penilaian3->lulus($validator, $nim);
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

        return $this->penilaian3->lulus($data, $nim);
    }
    public function import()
    {
        try {
            $import = $this->penilaian3->import();
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
