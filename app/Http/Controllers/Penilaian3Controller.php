<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\PesertaTahap2;
use App\Models\PesertaTahap3;
use App\Models\KriteriaTahap3;
use App\Models\PenilaianTahap3;
use App\Models\SubKriteriaTahap3;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class Penilaian3Controller extends Controller
{
    public function index()
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap3', 'PenilaianTahap3.SubKriteriaTahap3.KriteriaTahap3'])
            ->whereHas('PesertaTahap3')
            ->get();
        $kriteria = KriteriaTahap3::query()->with(['SubKriteriaTahap3'])
            ->get();
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
            $detail['nama_panggilan'] = $query->panggilan;
            $detail['e-mail'] = $query->email;
            $detail['nomor_hp'] = $query->no_hp;
            $detail['gender'] = $query->gender->gender;
            $detail['tempat_lahir'] = $query->tempat_lahir;
            $detail['tanggal_lahir'] = $query->tgl_lahir;
            $detail['fakultas'] = $query->fakultas->fakultas;
            $detail['jurusan'] = $query->jurusan->jurusan;
            $detail['bidang_fakultas'] = $query->Fakultas->BidangFakultas->bidang_fak;
            $detail['alamat_di_padang'] = $query->alamat_pdg;
            $query->detail = $detail;
            $query->lulus = $query->PesertaTahap3->lulus;
            $query->nilai = $query->PenilaianTahap3
                ->groupBy(['SubKriteriaTahap3.KriteriaTahap3.k_sc'])
                ->map(function ($query) {
                    if ($query->count() > 1) {
                        return $query->mapWithKeys(function ($sub) {
                            return [
                                $sub->SubKriteriaTahap3->sk_sc => $sub->nilai
                            ];
                        });
                    } elseif ($query->count() == 1) {
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
                } elseif ($query->count() == 1) {
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
                                $totalTemp += $v2 * $vSub['sub_kriteria'][""][$k2]; //ada kemungkinan bug terjadi disini karena $v2 memanggil array terakhir drpd memanggil array 'total'
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

        $response = [
            'message' => 'Data peserta tahap 3 OR XI',
            'data' => $total_k
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function calculation()
    {
        $kriteria = new Kriteria3Controller;
        $subkriteria = new SubKriteria3Controller;
        $fakultas = new FakultasController;

        $array['kriteria'] = $kriteria->index()->original;
        $array['subkriteria'] = $subkriteria->index()->original;
        $array['subkriteriatranspose'] = $subkriteria->transpose()->original;
        $array['penilaian'] = $this->index()->original;
        $array['fakultas'] = $fakultas->index()->original;

        $response = [
            'message' => 'Data penilaian tahap 3 OR XI beserta data pendukung',
            'data' => $array
        ];
        return response()->json($response, Response::HTTP_OK);
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

        try {
            if ($filterArray == null) {
                $response = [
                    'message' => 'Tidak ada data yg diimport'
                ];
            } else {
                $insert = PesertaTahap3::insert($filterArray);
                $response = [
                    'message' => 'Import peserta dari tahap 1 berhasil',
                    'data' => $insert
                ];
            }
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Import gagal: " . $e->getMessage()
            ]);
        }
    }

    public function lulus(Request $request, $id)
    {
        $lulus = PesertaTahap3::where('nim', $id)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'lulus' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $lulus->lulus = $request->lulus;
            $lulus->update();
            $response = [
                'message' => 'Kelulusan peserta updated',
                'data' => $lulus
            ];
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Update kelulusan failed: " . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $kriteria = KriteriaTahap3::get();
        $subkriteria = SubKriteriaTahap3::pluck('id_sk3')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k3] = SubKriteriaTahap3::where('id_k3', $k->id_k3)->get('id_sk3');
            $multi_sub = SubKriteriaTahap3::with('KriteriaTahap3')->where('id_k3', $k->id_k3)->get();
            $valid['nim'] = ['required'];
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k3]) > 1) {
                    $valid[$k->k_sc . '-' . $sk->sk_sc] = 'required|numeric|min:0';
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k3]) == 1) {
                    $valid[$k->k_sc] = 'required|numeric|min:0';
                    $nilai_sk[] = $k->k_sc;
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

        foreach (array_combine($subkriteria, $nilai_sk) as $sk => $ns) {
            $bulk_insert[] = [
                'nim' => $request->nim,
                'id_sk3' => $sk,
                'nilai' => $request->$ns
            ];
        }
        try {
            $penilaian2 =  PenilaianTahap3::insert($bulk_insert);
            $response = [
                'message' => 'Penilaian created',
                'data' => $penilaian2
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Create failed: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $kriteria = KriteriaTahap3::pluck('id_k3', 'k_sc');
        $subkriteria = SubKriteriaTahap3::pluck('id_sk3')->toArray();
        foreach ($kriteria as $nk => $k) {
            $sub_k['kriteria_' . $k] = SubKriteriaTahap3::where('id_k3', $k)->get('id_sk3');
            $multi_sub = SubKriteriaTahap3::join('kriteria_t3', 'sub_kriteria_t3.id_k3', '=', 'kriteria_t3.id_k3')
                ->where('kriteria_t3.id_k3', $k)->pluck('sub_kriteria_t3.id_sk3', 'sub_kriteria_t3.sk_sc');
            foreach ($multi_sub as $sk => $nsk) {
                if (count($sub_k['kriteria_' . $k]) > 1) {
                    $arr[$nk . '-' . $sk] = 'required|numeric|min:0';
                    $nilai_sk[] = $nk . '-' . $sk;
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $arr[$nk] = 'required|numeric|min:0';
                    $nilai_sk[] = $nk;
                }
            }
        }

        $validator = Validator::make($request->all(), $arr);
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($nilai_sk as $ns) {
            $bulk_update[] = [
                'nilai' => $request->$ns
            ];
        }
        $mass_update = '';

        foreach (array_combine($subkriteria, $bulk_update) as $sk => $bulk) {
            $mass_update .= PenilaianTahap3::where('nim', $id)->where('id_sk3', $sk)
                ->update($bulk);
        }

        try {
            $mass_update;
            $response = [
                'message' => 'Penilaian updated',
                'data' => $mass_update
            ];

            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Update failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap3', 'PenilaianTahap3.SubKriteriaTahap3.KriteriaTahap3'])
            ->whereHas('PesertaTahap3')
            ->get();
        $kriteria = KriteriaTahap3::query()->with(['SubKriteriaTahap3'])
            ->get();
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
                            return [
                                $sub->SubKriteriaTahap3->sk_sc => $sub->nilai
                            ];
                        });
                    } elseif ($query->count() == 1) {
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
                } elseif ($query->count() == 1) {
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

        $where = $total_k->where('nim', $id)->values();

        $response = [
            'message' => 'Detail salah satu peserta tahap 3 OR XI',
            'data' => $where
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
