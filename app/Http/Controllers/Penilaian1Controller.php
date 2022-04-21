<?php

namespace App\Http\Controllers;

use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
use App\Models\PesertaTahap1;
use App\Models\SubKriteriaTahap1;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Penilaian1Controller extends Controller
{
    //
    public function index()
    {
        // $subkriteria = SubKriteriaTahap1::pluck('sub_kriteria', 'id_sk1')->toArray();

        // $n = 0;
        // $selectQuery = '';
        // foreach ($subkriteria as $sid => $sk1) {
        //     $selectQuery .=
        //         'sum(case when nilai_t1.id_sk1 =
        //         (select distinct id_sk1 from nilai_t1 a order by 1 limit ' . $n . ',1)
        //         then nilai end) as \'' . $sk1 . '\',';
        //     $n++;
        // }
        // $selectQuery = rtrim($selectQuery, ",");

        // $peserta1 = PenilaianTahap1::select(
        //     'nilai_t1.nim',
        //     'pendaftar.nama',
        //     DB::raw($selectQuery)
        // )
        //     ->join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
        //     ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
        //     ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
        //     ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
        //     ->groupBy('nilai_t1.nim')
        //     ->get();

        // $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        // $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
        //     ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
        //     ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        $peserta1 = PesertaTahap1::join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
            ->join('jurusan', 'pendaftar.id_j', '=', 'jurusan.id_j')
            ->join('fakultas', 'jurusan.id_f', '=', 'fakultas.id_f')
            ->join('bidang_fak', 'fakultas.id_bf', '=', 'bidang_fak.id_bf')
            ->get([
                'peserta_t1.nim',
                'pendaftar.nama',
                'pendaftar.panggilan',
                'pendaftar.email',
                'gender.gender',
                'pendaftar.tempat_lahir',
                'pendaftar.tgl_lahir',
                'fakultas.fakultas',
                'jurusan.jurusan',
                'bidang_fak.bidang_fak',
                'pendaftar.alamat_pdg',
                'pendaftar.no_hp'
            ]);

        $f = 0;
        foreach ($peserta1 as $p) {
            $peserta[$f]['NIM'] = $p->nim;
            $peserta[$f]['Nama'] = $p->nama;
            $data_p['Nama Panggilan'] = $p->panggilan;
            $data_p['E-Mail'] = $p->email;
            $data_p['Nomor HP'] = $p->no_hp;
            $data_p['Gender'] = $p->gender;
            $data_p['Tempat Lahir'] = $p->tempat_lahir;
            $data_p['Tanggal Lahir'] = $p->tgl_lahir;
            $data_p['Fakultas'] = $p->fakultas;
            $data_p['Jurusan'] = $p->jurusan;
            $data_p['Bidang Fakultas'] = $p->bidang_fak;
            $data_p['Alamat di Padang'] = $p->alamat_pdg;
            $peserta[$f]['Detail'] = $data_p;
            foreach ($nm as $v) {
                if ($p->nim != $v->nim) {
                    $peserta[$f]['Nilai'] = 'Tidak ada data';
                } else {
                    foreach ($kriteria as $nk => $k) {
                        $match = ['nim' => $v->nim, 'id_k1' => $k];
                        $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                        if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                            $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                                ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sub_kriteria');
                            foreach ($multi_sub as $jsk => $ns) {
                                $match2 = ['nim' => $v->nim, 'id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns];
                                $m_sub[$jsk] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                    ->where($match2)->pluck('nilai')->first();
                                $nilaii[$nk] = $m_sub;
                            }
                        } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                            $nilaii[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                ->where($match)->select('nilai')->first()->nilai;
                        }
                    }
                    $peserta[$f]['Nilai'] = $nilaii;
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

    public function calculate()
    {
        $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        $i = 0;
        foreach ($nm as $v) {
            $nilai[$i]['NIM'] = $v->nim;
            $nilai[$i]['Nama'] = $v->nama;
            foreach ($kriteria as $nk => $k) {
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $match = ['nim' => $v->nim, 'id_k1' => $k];
                $max[$nk] = collect($nilai)->max($nk);
                $bobot_k[$nk] = KriteriaTahap1::where('kriteria', $nk)
                    ->pluck('bobot')->first();
                $bobot[$nk] = KriteriaTahap1::where('kriteria', $nk)
                    ->pluck('bobot', 'kriteria')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) {
                    $nilai[$i][$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where($match)->sum('nilai');
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $nilai[$i][$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where($match)->select('nilai')->first()->nilai;
                }
            }
            $i++;
        }

        $nilai_col = collect($nilai);
        $max_col = collect($max);
        $j = 0;
        foreach ($nm as $v) {
            $norm[$j]['NIM'] = $v->nim;
            $norm[$j]['Nama'] = $v->nama;
            foreach ($kriteria as $nk => $k) {
                $norm[$j][$nk] = number_format(
                    ($nilai_col->where('NIM', $v->nim)
                        ->pluck($nk)->first() / $max_col->get($nk)),
                    2
                );
                $calc[$nk] = number_format(
                    (collect($norm)->where('NIM', $v->nim)->pluck($nk)->first()
                        * collect($bobot)->get($nk)),
                    2
                );
            }
            $norm[$j]['Total'] = number_format(collect($calc)->sum(), 2);
            $j++;
        }
        $k = 0;

        foreach ($nm as $v) {
            $nilai_get = collect($nilai)->firstWhere('NIM', '=', $v->nim);
            $norm_get =  collect($norm)->firstWhere('NIM', '=', $v->nim);
            unset($nilai_get['NIM']);
            unset($nilai_get['Nama']);
            unset($norm_get['NIM']);
            unset($norm_get['Nama']);
            $final[$k]['NIM'] = $v->nim;
            $final[$k]['Nama'] = $v->nama;
            $final[$k]['Nilai'] = $nilai_get;
            $final[$k]['Normalisasi'] = $norm_get;
            $k++;
        }

        $response = [
            'message' => 'Tabel kalkulasi penilaian OR tahap 1',
            'data' => $final
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function test()
    {
        $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        $subkriteria = SubKriteriaTahap1::pluck('sub_kriteria', 'id_sk1');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        $e = 0;
        foreach ($nm as $v) {
            $krittt[$e]['NIM'] = $v->nim;
            $krittt[$e]['Nama'] = $v->nama;
            foreach ($kriteria as $nk => $k) {
                $match = ['nim' => $v->nim, 'id_k1' => $k];
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                        ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sub_kriteria');
                    foreach ($multi_sub as $jsk => $ns) {
                        $match2 = ['nim' => $v->nim, 'id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns];
                        $m_sub[$jsk] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where($match2)->pluck('nilai')->first();
                        $ms_max[$jsk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                            ->where(['id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->max('nilai');
                        $ms_bobot[$jsk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                            ->where(['kriteria_t1.id_k1' => $k, 'sub_kriteria_t1.id_sk1' => $ns])->pluck('sub_kriteria_t1.bobot')->first();
                        $nilaii[$nk] = $m_sub;
                        // $g = 0;
                        $ms_norm[$jsk] = number_format(collect($m_sub)->get($jsk) / collect($ms_max)->get($jsk), 2);
                        $ms_calc[$jsk] = collect($ms_norm)->get($jsk) * collect($ms_bobot)->get($jsk);
                        // $ms_total['Total'] = number_format(collect($ms_calc)->sum(), 2);
                        // foreach ($ms_total as $m) {
                        //     $ms_total_loop[$g][$nk] = $m;
                        // }
                        // $g++;
                        // $ms_max2[$nk] = collect($ms_total_loop)->min($nk);
                        // $ms_norm2[$nk] = collect($ms_total_loop)->get($nk) / collect($ms_max2)->get($nk);
                        // $ms_calc2 = number_format(collect($ms_norm2)->get($nk) * collect($n_bobot)->get($nk), 2);
                        // $normz[$nk] = array_merge($ms_norm, $ms_total);
                        $normz[$nk] = $ms_norm;
                        // $calc[$nk] = $ms_calc2;
                    }
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $nilaii[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where($match)->select('nilai')->first()->nilai;
                    $n_max[$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                        ->where(['id_k1' => $k])->select('nilai')->max('nilai');
                    $n_norm = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2);
                    $n_norm2[$nk] = number_format(collect($nilaii)->get($nk) / collect($n_max)->get($nk), 2); // untuk variabel kalkulasi nilai, menghindari array dalam array
                    // $n_calc = number_format(collect($n_norm2)->get($nk) * collect($n_bobot)->get($nk), 2);
                    $normz[$nk] = $n_norm;
                    // $calc[$nk] = $n_calc;
                }
            }
            $krittt[$e]['Nilai'] = $nilaii;
            $krittt[$e]['Normalisasi'] = $normz;
            $e++;
        }

        foreach ($krittt as $krt) {
            $normalization = collect($krittt)->pluck('Normalisasi');
            $calc = collect($normalization)->pluck('Forum Group Discussion');
            // $sum = collect($calc)->pluck();
        }

        return $calc;
    }

    public function store(Request $request)
    {
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();

        foreach ($subkriteria as $sk) {
            $valid['nim'] = ['required'];
            $valid['nilai_' . $sk] = ['required', 'numeric'];
            $nilai_sk[] = 'nilai_' . $sk;
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
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();
        foreach ($subkriteria as $sk) {
            $arr['nilai_' . $sk] = ['required', 'numeric'];
            $nilai_sk[] = 'nilai_' . $sk;
            foreach ($nilai_sk as $ns) {
                $bulk_update[] = [
                    'nilai' => $request->$ns
                ];
            }
        }

        $validator = Validator::make($request->all(), $arr);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // foreach ($subkriteria as $sk) {
        //     $nilai_sk[] = 'nilai_' . $sk;
        //     foreach ($nilai_sk as $ns) {
        //         $bulk_update[] = [
        //             'nilai' => $request->$ns
        //         ];
        //     }
        // }

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
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function show($id)
    {
        //
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();

        $n = 0;
        $selectQuery = '';
        foreach ($subkriteria as $sid) {
            $selectQuery .=
                'sum(case when nilai_t1.id_sk1 =
                (select distinct id_sk1 from nilai_t1 a order by 1 limit ' . $n . ',1)
                then nilai end) as \'' . $sid . '\',';
            $n++;
        }
        $selectQuery = rtrim($selectQuery, ",");

        $peserta1 = PenilaianTahap1::select(
            'nilai_t1.nim',
            'pendaftar.nama',
            'gender.gender',
            'fakultas.fakultas',
            DB::raw($selectQuery)
        )
            ->join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
            ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
            ->join('fakultas', 'pendaftar.id_f', '=', 'fakultas.id_f')
            ->join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
            ->groupBy('nilai_t1.nim')
            ->where('nilai_t1.nim', $id)
            ->get();

        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $peserta1
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
