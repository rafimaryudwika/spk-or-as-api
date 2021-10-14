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
        //
        //$pendaftar = Pendaftar::orderBy('tgl_daftar')->get();
        $pendaftar = Pendaftar::join('gender', 'pendaftar.id_g' , '=', 'gender.id_g')
        ->join('jurusan', 'pendaftar.id_j' , '=', 'jurusan.id_j')
        ->join('fakultas', 'jurusan.id_f' , '=', 'fakultas.id_f')
        ->join('bidang_fak', 'fakultas.id_bf' , '=', 'bidang_fak.id_bf')
        ->orderBy('tgl_daftar')
        ->get([
            'pendaftar.tgl_daftar',
            'pendaftar.nim',
            'pendaftar.nama',
            'gender.gender',
            'pendaftar.tgl_lahir',
            'fakultas.fakultas',
            'jurusan.jurusan'
        ]);
        $response = [
            'message' => 'Data pendaftar OR Sinema XI',
            'data' => $pendaftar
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'nama' =>['required'],
            'panggilan' =>['required'],
            'id_g' =>['required'],
            'tempat_lahir' =>['required'],
            'tgl_lahir' =>['required', 'date'],
            'alamat_pdg' =>['required'],
            'no_hp' =>['required', 'numeric'],
            'id_f' =>['required'],
            'id_j'=>['required']

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $pendaftar = Pendaftar::create($request->all());
            $response = [
                'message' => 'Pendaftar Masuk',
                'data' => $pendaftar
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e){
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
        $pendaftar = Pendaftar::findOrFail($id);
        $response = [
            'message'=> 'Data pendaftar',
            'data' => $pendaftar
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
