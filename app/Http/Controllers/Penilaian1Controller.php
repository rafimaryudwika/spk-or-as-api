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
        foreach ($peserta1 as $p) {
            $peserta[$f]['NIM'] = $p->Pendaftar->nim;
            $peserta[$f]['Nama'] = $p->Pendaftar->nama;
            $data_p['Nama Panggilan'] = $p->Pendaftar->panggilan;
            $data_p['E-Mail'] = $p->Pendaftar->email;
            $data_p['Nomor HP'] = $p->Pendaftar->no_hp;
            $data_p['Gender'] = $p->Pendaftar->Gender->gender;
            $data_p['Tempat Lahir'] = $p->Pendaftar->tempat_lahir;
            $data_p['Tanggal Lahir'] = $p->Pendaftar->tgl_lahir;
            $data_p['Fakultas'] = $p->Pendaftar->Fakultas->fakultas;
            $data_p['Jurusan'] = $p->Pendaftar->Jurusan->jurusan;
            $data_p['Bidang Fakultas'] = $p->Pendaftar->Fakultas->BidangFakultas->bidang_fak;
            $data_p['Alamat di Padang'] = $p->Pendaftar->alamat_pdg;
            $peserta[$f]['Detail'] = $data_p;
            foreach ($nm as $v) {
                if ($p->nim != $v->nim) {
                    $peserta[$f]['Nilai'] = 'Tidak ada data';
                } else {
                    foreach ($kriteria as $k) {
                        $match = ['nim' => $v->nim, 'id_k1' => $k->id_k1];
                        $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
                        if (count($sub_k['kriteria_' . $k->id_k1]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                            $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                            foreach ($multi_sub as $jsk) {
                                $match2 = ['nim' => $v->nim, 'id_k1' => $k->id_k1, 'sub_kriteria_t1.id_sk1' => $jsk->id_sk1];
                                $m_sub[$jsk->sub_kriteria] =  PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                    ->where($match2)->pluck('nilai')->first();
                                $nilaii[$k->kriteria] = $m_sub;
                            }
                        } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                            $nilaii[$k->kriteria] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
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
                        $ms_norm[$jsk] = number_format(collect($m_sub)->get($jsk) / collect($ms_max)->get($jsk), 2);
                        $ms_calc[$jsk] = number_format(collect($ms_norm)->get($jsk) * collect($ms_bobot)->get($jsk), 2);
                        $ms_total['Total'] = number_format(collect($ms_calc)->sum(), 2);
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
            $krittt[$e]['Nilai'] = $nilaii;
            $krittt[$e]['Normalisasi'] = $normz;
            $krittt[$e]['Total'] = $calc;
            $e++;
        }

        $f = 0;
        foreach ($krittt as $krtt) {
            $data[$f]['NIM'] = collect($krtt)->get('NIM');
            $data[$f]['Nama'] = collect($krtt)->get('Nama');
            $data[$f]['Nilai'] = collect($krtt)->get('Nilai');
            $data[$f]['Normalisasi'] = collect($krtt)->get('Normalisasi');
            foreach ($kriteria as $nk => $k) {
                $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
                $n_bobot[$nk] = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                    ->where(['kriteria_t1.id_k1' => $k])->pluck('kriteria_t1.bobot')->first();
                if (count($sub_k['kriteria_' . $k]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                    $skr[$nk] = number_format(collect(collect(collect($krtt)->get('Total'))->get($nk))->get('Total'), 2);
                    $skr_max[$nk] = (collect(collect($testin)->get($nk))->max());
                    $norms[$nk] = number_format((collect($skr)->get($nk) / collect($skr_max)->get($nk)) * collect($n_bobot)->get($nk), 2);
                } elseif (count($sub_k['kriteria_' . $k]) == 1) {
                    $norms[$nk] = collect(collect($krtt)->get('Total'))->get($nk);
                }
            }
            $data[$f]['Total'] = number_format(collect($norms)->sum(), 2);
            $f++;
        }


        $response = [
            'message' => 'Tabel kalkulasi penilaian OR tahap 1',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function test()
    {
    }

    public function store(Request $request)
    {
        $kriteria = KriteriaTahap1::get();
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1');

        foreach ($kriteria as $k) {
            $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
            $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
            $valid['nim'] = ['required'];
            foreach ($multi_sub as $sk) {
                if (count($sub_k['kriteria_' . $k->id_k1]) > 1) {
                    $valid[$k->kriteria . ' / ' . $sk->sub_kriteria] = ['required', 'numeric'];
                    $nilai_sk[] = $k->kriteria . ' / ' . $sk->sub_kriteria;
                } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                    $valid[$k->kriteria] = ['required', 'numeric'];
                    $nilai_sk[] = $k->kriteria;
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
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1');
        foreach ($kriteria as $nk => $k) {
            $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
            $multi_sub = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
                ->where('kriteria_t1.id_k1', $k)->pluck('sub_kriteria_t1.id_sk1', 'sub_kriteria_t1.sub_kriteria');
            foreach ($multi_sub as $sk => $nsk) {
                if (count($sub_k['kriteria_' . $k]) > 1) {
                    $arr[$nk . ' / ' . $sk] = ['required', 'numeric'];
                    $nilai_sk[] = $nk . ' / ' . $sk;
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
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function show($id)
    {
        $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        $peserta1 = PesertaTahap1::join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
            ->join('jurusan', 'pendaftar.id_j', '=', 'jurusan.id_j')
            ->join('fakultas', 'jurusan.id_f', '=', 'fakultas.id_f')
            ->join('bidang_fak', 'fakultas.id_bf', '=', 'bidang_fak.id_bf')
            ->where('pendaftar.nim', $id)
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
            'message' => 'Detail data peserta tahap 1 OR XI',
            'data' => $peserta
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
