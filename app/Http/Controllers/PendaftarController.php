<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $pendaftar = Pendaftar::join('gender', 'pendaftar.id_g', '=', 'gender.id_g')
        //     ->join('jurusan', 'pendaftar.id_j', '=', 'jurusan.id_j')
        //     ->join('fakultas', 'jurusan.id_f', '=', 'fakultas.id_f')
        //     ->join('bidang_fak', 'fakultas.id_bf', '=', 'bidang_fak.id_bf')
        //     ->orderBy('tgl_daftar')
        //     ->get([
        //         'pendaftar.tgl_daftar',
        //         'pendaftar.nim',
        //         'pendaftar.nama',
        //         'gender.gender',
        //         'pendaftar.tgl_lahir',
        //         'fakultas.fakultas',
        //         'jurusan.jurusan'
        //     ]);
        $pendaftar = $pendaftar = Pendaftar::with([
            'Fakultas',
            'Jurusan',
            'Gender',
            'Fakultas.BidangFakultas'
        ])->get();
        foreach ($pendaftar as $i => $p) {
            $data[$i]['tgl_daftar'] = $p->tgl_daftar;
            $data[$i]['nim'] = $p->nim;
            $data[$i]['nama'] = $p->nama;
            $data[$i]['panggilan'] = $p->panggilan;
            $data[$i]['gender'] = $p->Gender->gender;
            $data[$i]['tempat_lahir'] = $p->tempat_lahir;
            $data[$i]['tgl_lahir'] = $p->tgl_lahir;
            $data[$i]['alamat_pdg'] = $p->alamat_pdg;
            $data[$i]['email'] = $p->email;
            $data[$i]['no_hp'] = $p->no_hp;
            $data[$i]['fakultas'] = $p->Fakultas->fakultas;
            $data[$i]['jurusan'] = $p->Jurusan->jurusan;
            $data[$i]['bidang_fak'] = $p->Fakultas->BidangFakultas->bidang_fak;
            $data[$i]['daftar_ulang'] = $p->daftar_ulang;
        }
        $response = [
            'message' => 'Data pendaftar OR Sinema XI',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required'],
            'email' => ['required'],
            'nama' => ['required'],
            'panggilan' => ['required'],
            'id_g' => ['required'],
            'tempat_lahir' => ['required'],
            'tgl_lahir' => ['required', 'date'],
            'alamat_pdg' => ['required'],
            'no_hp' => ['required', 'numeric'],
            'id_f' => ['required'],
            'id_j' => ['required']

        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $pendaftar = Pendaftar::create($request->all());
            $response = [
                'message' => 'Pendaftar Masuk',
                'data' => $pendaftar
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $pendaftar = Pendaftar::with([
            'Fakultas',
            'Jurusan',
            'Gender',
            'Fakultas.BidangFakultas'
        ])->findOrFail($id);
        $data['tgl_daftar'] = $pendaftar->tgl_daftar;
        $data['nim'] = $pendaftar->nim;
        $data['nama'] = $pendaftar->nama;
        $data['panggilan'] = $pendaftar->panggilan;
        $data['gender'] = $pendaftar->Gender->gender;
        $data['tempat_lahir'] = $pendaftar->tempat_lahir;
        $data['tgl_lahir'] = $pendaftar->tgl_lahir;
        $data['alamat_pdg'] = $pendaftar->alamat_pdg;
        $data['email'] = $pendaftar->email;
        $data['no_hp'] = $pendaftar->no_hp;
        $data['fakultas'] = $pendaftar->Fakultas->fakultas;
        $data['jurusan'] = $pendaftar->Jurusan->jurusan;
        $data['bidang_fak'] = $pendaftar->Fakultas->BidangFakultas->bidang_fak;
        $data['daftar_ulang'] = $pendaftar->daftar_ulang;
        $response = [
            'message' => 'Data pendaftar',
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
