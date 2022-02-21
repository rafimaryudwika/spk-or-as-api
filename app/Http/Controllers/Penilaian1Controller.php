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
        $subkriteria = SubKriteriaTahap1::pluck('id_sk1', 'sub_kriteria');

        $selectQuery = '';

        foreach ($subkriteria as $key => $sk1) {
            $selectQuery =
                'sum( case when nilai_t1.id_sk1 = ' . $sk1 . '
            then nilai_t1.nilai else 0 end) AS \'' . $key . '\'';
        }
        $peserta1 = PenilaianTahap1::select(
            'peserta_t1.nim',
            'pendaftar.nama',
            'nilai_t1.id_sk1',
            'nilai_t1.nilai',
            DB::raw($selectQuery)
        )
            ->join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
            ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')

            ->orderBy('nim',  'asc')
            ->orderBy('id_sk1', 'asc')
            ->groupBy('nim')
            ->get([
                'peserta_t1.nim',
                'pendaftar.nama',
                'nilai_t1.id_sk1',
                'nilai_t1.nilai'
            ]);

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

    public function sk1_table()
    {
        $subkriteria = SubKriteriaTahap1::join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
            ->get([
                'sub_kriteria_t1.id_sk1',
                'sub_kriteria_t1.sub_kriteria',
                'sub_kriteria_t1.bobot',
            ]);
        $response = [
            'message' => 'Tabel sub-kriteria tahap 1',
            'data' => $subkriteria
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function krit1_table()
    {
        $kriteria = KriteriaTahap1::all();
        $response = [
            'message' => 'Table kriteria tahap 1',
            'data' => $kriteria
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function calculate()
    {
        // $sk_pluck = $sk = SubKriteriaTahap1::
        // join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
        // ->pluck(
        //    'sub_kriteria_t1.id_sk1');

        // $sk_pluck->all();

        // $subkriteria = SubKriteriaTahap1::all();

        $selectQuery = '';
        // foreach($subkriteria as $sk1{
        //    $selectQuery.="sum( case when nilai_t1.id_sk1 = '".$sk1->id_sk1."' then nilai_t1.nilai else 0 end) as '".$sk1->subkriteria."'','";
        // }

        // $kriteria = KriteriaTahap1::all();
        // $transpose=[];




        // foreach ($subkriteria as $sk1) {
        //     $selectQuery = 'sum( case when nilai_t1.id_sk1 = ' . $sk1->id_sk1 . '  then nilai_t1.nilai else 0 end) AS \'' . $sk1->sub_kriteria . '\'';
        // }

        //$trans_array = array($transpose);

        $penilaian1 = PenilaianTahap1::select(
            'nilai_t1.nim',
            'pendaftar.nama',
            DB::raw($selectQuery)
        )
            ->join('peserta_t1', 'nilai_t1.nim', '=', 'peserta_t1.nim')
            ->join('pendaftar', 'peserta_t1.nim', '=', 'pendaftar.nim')
            ->join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
            ->join('kriteria_t1', 'sub_kriteria_t1.id_k1', '=', 'kriteria_t1.id_k1')
            ->groupBy('nilai_t1.nim')
            ->get([
                'nilai_t1.nim',
                'nilai_t1.nilai',
                'nilai_t1.id_sk1'
            ]);


        $response = [
            'message' => 'id',
            'data' => $selectQuery
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required'],
            'id_sk1' => ['required', 'numeric'],
            'nilai' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $penilaian1 =  PenilaianTahap1::create($request->all());
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

    public function store_new(Request $request, $id)
    {
        //
        $data = $request->except('_token');

        $nim = $data['nim'];
        $id_sk1 = $data['id_sk1'];
        foreach ($id_sk1 as $key => $value) {
            echo $key . ' - ' . $value . '<br>';
            $objData = new stdClass();
            $objData->nim = $id;
            $objData->id_sk1 = $key;
            $objData->nilai = $value;
            $objArray[] = $objData;
        }
        foreach ($objArray as $data) {
            PenilaianTahap1::create([
                'nim' => $data->nim,
                'id_sk1' => $data->id_sk1,
                'nilai' => $data->nilai
            ]);
        }
        // $mahasiswa = Mahasiswa::find($id);
        // var_dump($mahasiswa);exit;
        // $mahasiswa->crip()->sync($data);
    }


    public function update(Request $request, $id)
    {
        $penilaian1 = PenilaianTahap1::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nim' => ['required'],
            'id_sk1' => ['required', 'numeric'],
            'nilai' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $penilaian1->update($request->all());
            $response = [
                'message' => 'Penilaian updated',
                'data' => $penilaian1
            ];

            return response()->json($response, Response::HTTP_OK); //code...
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function update_new(Request $request, $id)
    {
        $data = $request->except('_token');
        $nilai_id = $data['id_nilai'];
        $kriteria_id = $data['kriteria_id'];
        foreach ($nilai_id as $nilai) {
            $nilaiData = new stdClass();
            $nilaiData->id = $nilai;
            $nilaiArray[] = $nilaiData;
        }

        $i = 0;
        foreach ($kriteria_id as $key => $value) {
            $objData = new stdClass();
            $objData->id_nilai = $nilaiArray[$i]->id;
            $objData->mahasiswa_id = $id;
            $objData->kriteria_id = $key;
            $objData->nilai = $value;
            $objArray[] = $objData;
            $i++;
        }

        foreach ($objArray as $data) {
            $save = PenilaianTahap1::find($data->id_nilai);
            $save->nilai_alt = $data->nilai;
        }

        $validator = Validator::make($request->all(), [
            'nim' => ['required'],
            'id_sk1' => ['required', 'numeric'],
            'nilai' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $save->update($request->all());
            $response = [
                'message' => 'Penilaian updated',
                'data' => $save
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
        $pendaftar = PenilaianTahap1::findOrFail($id);
        $response = [
            'message' => 'Detail Penilaian Peserta',
            'data' => $pendaftar
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
