<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PesertaTahap1;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\SubKriteriaTahap1;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class Penilaian1Controller extends Controller
{
    //
    public function index()
    {
        $kriteria = KriteriaTahap1::get();
        $nm = PenilaianTahap1::groupBy('nim')->get('nim');
        $peserta1 = PesertaTahap1::with([
            'Pendaftar',
            'Pendaftar.Gender',
            'Pendaftar.Fakultas',
            'Pendaftar.Jurusan',
            'Pendaftar.Fakultas.BidangFakultas'
        ])->get();
        $f = 0;
        foreach ($peserta1 as $i => $p) {
            $peserta[$i]['nim'] = $p->Pendaftar->nim;
            $peserta[$i]['nama'] = $p->Pendaftar->nama;
            $data_p['nama_panggilan'] = $p->Pendaftar->panggilan;
            $data_p['e_mail'] = $p->Pendaftar->email;
            $data_p['nomor_hp'] = $p->Pendaftar->no_hp;
            $data_p['gender'] = $p->Pendaftar->Gender->gender;
            $data_p['tempat_lahir'] = $p->Pendaftar->tempat_lahir;
            $data_p['tanggal_lahir'] = $p->Pendaftar->tgl_lahir;
            $data_p['fakultas'] = $p->Pendaftar->Fakultas->fakultas;
            $data_p['jurusan'] = $p->Pendaftar->Jurusan->jurusan;
            $data_p['bidang_fakultas'] = $p->Pendaftar->Fakultas->BidangFakultas->bidang_fak;
            $data_p['alamat_di_padang'] = $p->Pendaftar->alamat_pdg;
            $peserta[$i]['detail'] = $data_p;
            if (count($nm) === 0) {
                $peserta[$i]['nilai'] = 'nodata';
            } else {
                foreach ($nm as $v) {
                    if ($p->nim != $v->nim) {
                        $peserta[$i]['nilai'] = 'nodata';
                    } else {
                        foreach ($kriteria as $k) {
                            $match = ['nim' => $v->nim, 'id_k1' => $k->id_k1];
                            $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
                            if (count($sub_k['kriteria_' . $k->id_k1]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                                $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                                foreach ($multi_sub as $jsk) {
                                    $match2 = ['nim' => $v->nim, 'id_k1' => $k->id_k1, 'sub_kriteria_t1.id_sk1' => $jsk->id_sk1];
                                    $m_sub[Str::snake($jsk->sub_kriteria)] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                        ->where($match2)->pluck('nilai')->first();
                                    $nilaii[Str::snake($k->kriteria)] = $m_sub;
                                }
                            } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                                $nilaii[Str::snake($k->kriteria)] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                    ->where($match)->select('nilai')->first()->nilai;
                            }
                        }
                        $peserta[$i]['nilai'] = $nilaii;
                        $peserta[$i]['lulus'] = $p->lulus;
                        break;
                    }
                }
            }
        }

        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $peserta
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function calculate()
    {
        $kriteria = KriteriaTahap1::pluck('id_k1', 'k_sc');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama', 'peserta_t1.lulus']);

        $e = 0;
        foreach ($nm as $v) {
            $krittt[$e]['nim'] = $v->nim;
            $krittt[$e]['nama'] = $v->nama;
            $peserta1 = PesertaTahap1::with([
                'Pendaftar',
                'Pendaftar.Gender',
                'Pendaftar.Fakultas',
                'Pendaftar.Jurusan',
                'Pendaftar.Fakultas.BidangFakultas'
            ])->where('nim', $v->nim)->get();
            foreach ($peserta1 as $p) {
                $data_p['nama_panggilan'] = $p->Pendaftar->panggilan;
                $data_p['e_mail'] = $p->Pendaftar->email;
                $data_p['nomor_hp'] = $p->Pendaftar->no_hp;
                $data_p['gender'] = $p->Pendaftar->Gender->gender;
                $data_p['tempat_lahir'] = $p->Pendaftar->tempat_lahir;
                $data_p['tanggal_lahir'] = $p->Pendaftar->tgl_lahir;
                $data_p['fakultas'] = $p->Pendaftar->Fakultas->fakultas;
                $data_p['jurusan'] = $p->Pendaftar->Jurusan->jurusan;
                $data_p['bidang_fakultas'] = $p->Pendaftar->Fakultas->BidangFakultas->bidang_fak;
                $data_p['alamat_di_padang'] = $p->Pendaftar->alamat_pdg;
            }
            $krittt[$e]['detail'] = $data_p;
            foreach ($kriteria as $nk => $k) {
                $match = ['nim' => $v->nim, 'id_k1' => $k];
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                        ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sk_sc');
                    foreach ($multi_sub as $jsk => $ns) {
                        $match2 = ['nim' => $v->nim, 'id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns];
                        $m_sub[$jsk] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where($match2)->pluck('nilai')->first();
                        $ms_max[$jsk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where(['id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->max('nilai');
                        $ms_bobot[$jsk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                            ->where(['kriteria_t1.id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->pluck('sub_kriteria_t1.bobot')->first();
                        $nilaii[$nk] = $m_sub;
                        $ms_norm[$jsk] = number_format(collect($m_sub)->get($jsk) / collect($ms_max)->get($jsk), 2);
                        $ms_calc[$jsk] = number_format(collect($ms_norm)->get($jsk) * collect($ms_bobot)->get($jsk), 2);
                        $ms_total['total'] = number_format(collect($ms_calc)->sum(), 2);
                        $normz[$nk] = $ms_norm;
                        $calc[$nk] = $ms_total;
                    }
                    foreach ($ms_total as $mst) {
                        $testin[$nk][] = $mst;
                    }
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $nilaii[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where($match)->select('nilai')->first()->nilai;
                    $n_max[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where(['id_k1' => $k])->select('nilai')->max('nilai');
                    $n_norm = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2);
                    $n_norm2[$nk] = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2); // untuk variabel kalkulasi nilai, menghindari array dalam array
                    $n_calc = number_format(collect($n_norm2)->get($nk) * collect($n_bobot)->get($nk), 2);
                    $normz[$nk] = $n_norm;
                    $calc[$nk] = $n_calc;
                }
            }
            $krittt[$e]['nilai'] = $nilaii;
            $krittt[$e]['normalisasi'] = $normz;
            $krittt[$e]['total'] = $calc;
            $krittt[$e]['lulus'] = $v->lulus;
            $e++;
        }

        $f = 0;
        foreach ($krittt as $krtt) {
            $data[$f]['nim'] = collect($krtt)->get('nim');
            $data[$f]['nama'] = collect($krtt)->get('nama');
            $data[$f]['detail'] = collect($krtt)->get('detail');
            $data[$f]['nilai'] = collect($krtt)->get('nilai');
            $data[$f]['normalisasi'] = collect($krtt)->get('normalisasi');
            foreach ($kriteria as $nk => $k) {
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $skr[$nk] = number_format(collect(collect(collect($krtt)->get('total'))->get($nk))->get('total'), 2);
                    $skr_max[$nk] = (collect(collect($testin)->get($nk))->max());
                    $norms[$nk] = number_format((collect($skr)->get($nk) / collect($skr_max)->get($nk)) * collect($n_bobot)->get($nk), 2);
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $norms[$nk] = collect(collect($krtt)->get('total'))->get($nk);
                }
            }
            $data[$f]['total'] = number_format(collect($norms)->sum(), 2);
            $data[$f]['lulus'] = collect($krtt)->get('lulus');
            $f++;
        }

        $response = [
            'message' => 'Tabel kalkulasi penilaian OR tahap 1',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
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

        try {
            if ($filterArray == null) {
                $response = [
                    'message' => 'Tidak ada data yg diimport'
                ];
            } else {
                $insert = PesertaTahap1::insert($filterArray);
                $response = [
                    'message' => 'Import peserta dari pendaftar berhasil',
                    'data' => $insert
                ];
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Import gagal: " . $e->getMessage()
            ]);
        }
    }

    public function lulus(Request $request, $id)
    {
        $lulus = PesertaTahap1::where('nim', $id)->firstOrFail();
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
        $kriteria = KriteriaTahap1::get();
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
            $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
            $valid['nim'] = ['required'];
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k1]) > 1) {
                    $valid[$k->k_sc . '-' . $sk->sk_sc] = ['required', 'numeric'];
                    $nilai_sk[] = $k->k_sc . '-' . $sk->sk_sc;
                } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                    $valid[$k->k_sc] = ['required', 'numeric'];
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
                'id_sk1' => $sk,
                'nilai' => $request->$ns
            ];
        }
        try {
            $penilaian1 =  PenilaianTahap1::insert($bulk_insert);
            $response = [
                'message' => 'Penilaian created',
                'data' => $penilaian1
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
        $kriteria = KriteriaTahap1::pluck('id_k1', 'k_sc');
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();
        foreach ($kriteria as $nk => $k) {
            $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
            $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sk_sc');
            foreach ($multi_sub as $sk => $nsk) {
                if (count($sub_k['kriteria_' . $k]) > 1) {
                    $arr[$nk . '-' . $sk] = ['required', 'numeric'];
                    $nilai_sk[] = $nk . '-' . $sk;
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $arr[$nk] = ['required', 'numeric'];
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
            $mass_update .= PenilaianTahap1::where('nim', $id)->where('id_sk1', $sk)
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
        $kriteria = KriteriaTahap1::pluck('id_k1', 'k_sc');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')->where('nilai_t1.nim', '=', $id)
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama', 'peserta_t1.lulus']);

        $e = 0;
        foreach ($nm as $v) {
            $krittt[$e]['nim'] = $v->nim;
            $krittt[$e]['nama'] = $v->nama;
            foreach ($kriteria as $nk => $k) {
                $match = ['nim' => $v->nim, 'id_k1' => $k];
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                        ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sk_sc');
                    foreach ($multi_sub as $jsk => $ns) {
                        $match2 = ['nim' => $v->nim, 'id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns];
                        $m_sub[$jsk] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where($match2)->pluck('nilai')->first();
                        $ms_max[$jsk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where(['id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->max('nilai');
                        $ms_bobot[$jsk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                            ->where(['kriteria_t1.id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->pluck('sub_kriteria_t1.bobot')->first();
                        $nilaii[$nk] = $m_sub;
                        $ms_norm[$jsk] = number_format(collect($m_sub)->get($jsk) / collect($ms_max)->get($jsk), 2);
                        $ms_calc[$jsk] = number_format(collect($ms_norm)->get($jsk) * collect($ms_bobot)->get($jsk), 2);
                        $ms_total['total'] = number_format(collect($ms_calc)->sum(), 2);
                        $normz[$nk] = $ms_norm;
                        $calc[$nk] = $ms_total;
                    }
                    foreach ($ms_total as $mst) {
                        $testin[$nk][] = $mst;
                    }
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $nilaii[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where($match)->select('nilai')->first()->nilai;
                    $n_max[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where(['id_k1' => $k])->select('nilai')->max('nilai');
                    $n_norm = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2);
                    $n_norm2[$nk] = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2); // untuk variabel kalkulasi nilai, menghindari array dalam array
                    $n_calc = number_format(collect($n_norm2)->get($nk) * collect($n_bobot)->get($nk), 2);
                    $normz[$nk] = $n_norm;
                    $calc[$nk] = $n_calc;
                }
            }
            $krittt[$e]['nilai'] = $nilaii;
            $krittt[$e]['normalisasi'] = $normz;
            $krittt[$e]['total'] = $calc;
            $krittt[$e]['lulus'] = $v->lulus;
            $e++;
        }

        $f = 0;
        foreach ($krittt as $krtt) {
            $data[$f]['nim'] = collect($krtt)->get('nim');
            $data[$f]['nama'] = collect($krtt)->get('nama');
            $data[$f]['nilai'] = collect($krtt)->get('nilai');
            $data[$f]['normalisasi'] = collect($krtt)->get('normalisasi');
            foreach ($kriteria as $nk => $k) {
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $skr[$nk] = number_format(collect(collect(collect($krtt)->get('total'))->get($nk))->get('total'), 2);
                    $skr_max[$nk] = (collect(collect($testin)->get($nk))->max());
                    $norms[$nk] = number_format((collect($skr)->get($nk) / collect($skr_max)->get($nk)) * collect($n_bobot)->get($nk), 2);
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $norms[$nk] = collect(collect($krtt)->get('total'))->get($nk);
                }
            }
            $data[$f]['total'] = number_format(collect($norms)->sum(), 2);
            $data[$f]['lulus'] = collect($krtt)->get('lulus');
            $f++;
        }

        $response = [
            'message' => 'Detail salah satu peserta tahap 1 OR XI',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function show2($id)
    {
        $kriteria = KriteriaTahap1::get();
        $nm = PenilaianTahap1::groupBy('nim')->get('nim');
        $peserta1 = PesertaTahap1::with([
            'Pendaftar',
            'Pendaftar.Gender',
            'Pendaftar.Fakultas',
            'Pendaftar.Jurusan',
            'Pendaftar.Fakultas.BidangFakultas'
        ])->where('nim', $id)->get();
        $f = 0;
        foreach ($peserta1 as $p) {
            $peserta[$f]['nim'] = $p->Pendaftar->nim;
            $peserta[$f]['nama'] = $p->Pendaftar->nama;
            $data_p['nama_panggilan'] = $p->Pendaftar->panggilan;
            $data_p['e_mail'] = $p->Pendaftar->email;
            $data_p['nomor_hp'] = $p->Pendaftar->no_hp;
            $data_p['gender'] = $p->Pendaftar->Gender->gender;
            $data_p['tempat_lahir'] = $p->Pendaftar->tempat_lahir;
            $data_p['tanggal_lahir'] = $p->Pendaftar->tgl_lahir;
            $data_p['fakultas'] = $p->Pendaftar->Fakultas->fakultas;
            $data_p['jurusan'] = $p->Pendaftar->Jurusan->jurusan;
            $data_p['bidang_fakultas'] = $p->Pendaftar->Fakultas->BidangFakultas->bidang_fak;
            $data_p['alamat_di_padang'] = $p->Pendaftar->alamat_pdg;
            $peserta[$f]['detail'] = $data_p;
            foreach ($nm as $v) {
                if ($p->nim != $v->nim) {
                    $peserta[$f]['nilai'] = 'nodata';
                } else {
                    foreach ($kriteria as $k) {
                        $match = ['nim' => $v->nim, 'id_k1' => $k->id_k1];
                        $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
                        if (count($sub_k['kriteria_' . $k->id_k1]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                            $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                            foreach ($multi_sub as $jsk) {
                                $match2 = ['nim' => $v->nim, 'id_k1' => $k->id_k1, 'sub_kriteria_t1.id_sk1' => $jsk->id_sk1];
                                $m_sub[Str::snake($jsk->sub_kriteria)] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                    ->where($match2)->pluck('nilai')->first();
                                $nilaii[Str::snake($k->kriteria)] = $m_sub;
                            }
                        } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                            $nilaii[Str::snake($k->kriteria)] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                ->where($match)->select('nilai')->first()->nilai;
                        }
                    }
                    $peserta[$f]['nilai'] = $nilaii;
                    $peserta[$f]['lulus'] = $p->lulus;
                    break;
                }
            }
            $f++;
        }

        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $peserta
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
