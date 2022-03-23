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
use stdClass;

class Penilaian1Controller extends Controller
{
    //
    public function index()
    {
        $subkriteria = SubKriteriaTahap1::pluck('sub_kriteria', 'id_sk1')->toArray();

        $n = 0;
        $selectQuery = '';
        foreach ($subkriteria as $sid => $sk1) {
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
            DB::raw($selectQuery)
        )
            ->join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
            ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
            ->groupBy('nilai_t1.nim')
            ->get();

        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $peserta1
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function penilaian1()
    {
        //
        $penilaian1 = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
            ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
            ->groupBy('nilai_t1.nim')
            ->get([
                'nilai_t1.nim',
                'nilai_t1.nilai',
                'nilai_t1.id_sk1'
            ]);

        $peserta1 = PesertaTahap1::join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
            ->join('jurusan', 'pendaftar.id_j', '=', 'jurusan.id_j')
            ->join('fakultas', 'jurusan.id_f', '=', 'fakultas.id_f')
            ->join('bidang_fak', 'fakultas.id_bf', '=', 'bidang_fak.id_bf')
            ->get([
                'peserta_t1.nim',
                'pendaftar.nama',

            ]);



        $response = [
            'message' => 'Data pendaftar OR Sinema XI',
            'data' => $penilaian1
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function peserta()
    {
        $peserta1 = PesertaTahap1::join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
            ->join('jurusan', 'pendaftar.id_j', '=', 'jurusan.id_j')
            ->join('fakultas', 'jurusan.id_f', '=', 'fakultas.id_f')
            ->join('bidang_fak', 'fakultas.id_bf', '=', 'bidang_fak.id_bf')
            ->get([
                'peserta_t1.nim',
                'pendaftar.nama',
                'gender.gender',
                'pendaftar.tgl_lahir',
                'fakultas.fakultas',
                'jurusan.jurusan',
                'bidang_fak.bidang_fak'
            ]);
        $response = [
            'message' => 'Tabel sub-kriteria tahap 1',
            'data' => $peserta1
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function nilai_kriteria()
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

        $response = [
            'message' => 'Tabel nilai tahap 1',
            'data' => $nilai
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function calculate()
    {
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();

        $max = '';

        foreach ($subkriteria as $sk) {
            $max .= PenilaianTahap1::max('nilai')
                ->where($sk)
                ->get();
        }

        $response = [
            'message' => 'Tabel sub-kriteria tahap 1',
            'data' => $max
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function test(Request $request)
    {
        // $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();


        // $valid_nim = [
        //     'nim' => ['required']
        // ];

        // $arr = [];

        // foreach ($subkriteria as $sk => $id) {
        //     $arr['nilai_' . $sk] = ['required', 'numeric'];
        //     $valid_new = array_merge($valid_nim, $arr);
        // }
        // $validator = Validator::make($request->all(), $valid_new);

        // $bulk_insert = [];

        // foreach ($subkriteria as $sk) {
        //     $nilai_sk[] = 'nilai_' . $sk;
        // }

        // foreach (array_combine($subkriteria, $nilai_sk) as $sk => $ns) {
        //     $bulk_insert[] = [
        //         'nim => $request->nim',
        //         'id_sk1 =>' . $sk,
        //         'nilai => $request->' . $ns
        //     ];
        // }

        // $penilaian1 = PenilaianTahap1::findOrFail($id);
        // return $penilaian1;



        // $id = 1810112048;
        // foreach ($subkriteria as $sk) {
        //     $nilai_sk[] = 'nilai_' . $sk;
        // }

        // foreach ($nilai_sk as $ns) {
        //     $bulk_update[] = [
        //         'nilai' => $request->$ns
        //     ];
        // }

        // $mass_update = '';

        // foreach (array_combine($subkriteria, $bulk_update) as $sk => $bulk) {
        //     $mass_update .= PenilaianTahap1::where('nim', $id)->where('id_sk1', $sk)
        //         ->update($bulk);
        // }





        // foreach ($subkriteria as $sk) {
        //     $max .= PenilaianTahap1::where('nilai_t1.id_sk1', '=', $sk)
        //         ->max('nilai');
        // }

        // foreach ($subkriteria as $sk) {
        //     $sum .= PenilaianTahap1::where('nilai_t1.id_sk1', '=', $sk)
        //         ->sum('nilai');
        // }

        // $nim = 1810112048;
        // $kriteria = KriteriaTahap1::pluck('id_k1', 'kriteria');
        // $nm = PenilaianTahap1::join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
        //     ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
        //     ->groupBy('nilai_t1.nim')->get(['nilai_t1.nim', 'pendaftar.nama']);

        // $i = 0;
        // foreach ($nm as $v) {
        //     $nilai[$i]['NIM'] = $v->nim;
        //     $nilai[$i]['Nama'] = $v->nama;
        //     foreach ($kriteria as $nk => $k) {
        //         $sub_k['kriteria_' . $k] = SubKriteriaTahap1::where('id_k1', $k)->get('id_sk1');
        //         $match = ['nim' => $v->nim, 'id_k1' => $k];
        //         if (count($sub_k['kriteria_' . $k]) > 1) {
        //             $nilai[$i][$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
        //                 ->where($match)->sum('nilai');
        //         } elseif (count($sub_k['kriteria_' . $k]) == 1) {
        //             $nilai[$i][$nk] = PenilaianTahap1::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
        //                 ->where($match)->select('nilai')->first()->nilai;
        //         }
        //     }
        //     $i++;
        // }

        // $nm_nim[] = 'nim';

        // foreach ($kriteria as $k => $v) {
        //     $krit[] = $k;
        // }
        // $krit = array_merge($nm_nim, $krit);

        // $nilai_new = array_combine($krit, $nilai);
        // $nilai_new = array_combine($column, $nilai);
        // $collect_nilai = collect($nilai);
        // $nama_k = KriteriaTahap1::pluck('kriteria')->toArray();
        // $arrayComb = array_combine($nama_k, $nilai);
        // $arrayWithKey = $collect_nilai->map(function ($value, $nama_k) {
        //     return [
        //         "$nama_k->kriteria" => $value
        //     ];
        // });



        // $response = [
        //     'message' => 'Tabel nilai tahap 1',
        //     'data' => $nilai
        // ];
        // return response()->json($response, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1')->toArray();

        $valid_nim = [
            'nim' => ['required']
        ];

        foreach ($subkriteria as $sk) {
            $arr['nilai_' . $sk] = ['required', 'numeric'];
            $valid_new = array_merge($valid_nim, $arr);
        }

        $validator = Validator::make($request->all(), $valid_new);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($subkriteria as $sk) {
            $nilai_sk[] = 'nilai_' . $sk;
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
        }

        $validator = Validator::make($request->all(), $arr);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($subkriteria as $sk) {
            $nilai_sk[] = 'nilai_' . $sk;
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
