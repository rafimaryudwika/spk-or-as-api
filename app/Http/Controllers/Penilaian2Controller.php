<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\PesertaTahap1;
use App\Models\PesertaTahap2;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class Penilaian2Controller extends Controller
{
    public function index()
    {
        $pendaftar = Pendaftar::query()
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap2', 'PenilaianTahap2.SubKriteriaTahap2.KriteriaTahap2'])
            ->whereHas('PesertaTahap2')
            ->get();
        $kriteria = KriteriaTahap2::query()->with(['SubKriteriaTahap2'])
            ->get();
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
                            return [
                                $sub->SubKriteriaTahap2->sk_sc => $sub->nilai
                            ];
                        });
                    } elseif ($query->count() == 1) {
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

        $response = [
            'message' => 'Data peserta tahap 2 OR XI',
            'data' => $total_k
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function calculation()
    {
        $kriteria = new Kriteria2Controller;
        $subkriteria = new SubKriteria2Controller;
        $fakultas = new FakultasController;

        $array['kriteria'] = $kriteria->index()->original;
        $array['subkriteria'] = $subkriteria->index()->original;
        $array['subkriteriatranspose'] = $subkriteria->transpose()->original;
        $array['penilaian'] = $this->index()->original;
        $array['fakultas'] = $fakultas->index()->original;

        $response = [
            'message' => 'Data penilaian tahap 2 OR XI beserta data pendukung',
            'data' => $array
        ];
        return response()->json($response, Response::HTTP_OK);
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

        try {
            if ($filterArray == null) {
                $response = [
                    'message' => 'Tidak ada data yg diimport'
                ];
            } else {
                $insert = PesertaTahap2::insert($filterArray);
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
        $lulus = PesertaTahap2::where('nim', $id)->firstOrFail();
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
        $kriteria = KriteriaTahap2::get();
        $subkriteria = SubKriteriaTahap2::pluck('id_sk2')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k2] = SubKriteriaTahap2::where('id_k2', $k->id_k2)->get('id_sk2');
            $multi_sub = SubKriteriaTahap2::with('KriteriaTahap2')->where('id_k2', $k->id_k2)->get();
            $valid['nim'] = ['required'];
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k2]) > 1) {
                    $valid[$k->k_sc . '-' . $sk->sk_sc] = ['required', 'numeric', 'min:0'];
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k2]) == 1) {
                    $valid[$k->k_sc] = ['required', 'numeric', 'min:0'];
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
                'id_sk2' => $sk,
                'nilai' => $request->$ns
            ];
        }
        try {
            $penilaian2 =  PenilaianTahap2::insert($bulk_insert);
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
        $kriteria = KriteriaTahap2::pluck('id_k2', 'k_sc');
        $subkriteria = SubKriteriaTahap2::pluck('id_sk2')->toArray();
        foreach ($kriteria as $nk => $k) {
            $sub_k['kriteria_' . $k] = SubKriteriaTahap2::where('id_k2', $k)->get('id_sk2');
            $multi_sub = SubKriteriaTahap2::join('kriteria_t2', 'sub_kriteria_t2.id_k2', '=', 'kriteria_t2.id_k2')
                ->where('kriteria_t2.id_k2', $k)->pluck('sub_kriteria_t2.id_sk2', 'sub_kriteria_t2.sk_sc');
            foreach ($multi_sub as $sk => $nsk) {
                if (count($sub_k['kriteria_' . $k]) > 1) {
                    $arr[$nk . '-' . $sk] = ['required', 'numeric', 'min:0'];
                    $nilai_sk[] = $nk . '-' . $sk;
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $arr[$nk] = ['required', 'numeric', 'min:0'];
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
            $mass_update .= PenilaianTahap2::where('nim', $id)->where('id_sk2', $sk)
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
            ->with(['Gender', 'Jurusan', 'Fakultas.BidangFakultas', 'PesertaTahap2', 'PenilaianTahap2.SubKriteriaTahap2.KriteriaTahap2'])
            ->whereHas('PesertaTahap2')
            ->get();
        $kriteria = KriteriaTahap2::query()->with(['SubKriteriaTahap2'])
            ->get();
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
            $test['e_mail'] = $query->email;
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
                    } elseif ($query->count() == 1) {
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
            'message' => 'Detail salah satu peserta tahap 2 OR XI',
            'data' => $where
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
