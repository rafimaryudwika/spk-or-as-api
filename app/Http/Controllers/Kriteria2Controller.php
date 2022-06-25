<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap2;
use App\Models\SubKriteriaTahap2;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class Kriteria2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kriteria = KriteriaTahap2::get([
            'kriteria_t2.id_k2',
            'kriteria_t2.kode',
            'kriteria_t2.kriteria',
            'kriteria_t2.k_sc',
            'kriteria_t2.bobot'
        ]);

        $response = [
            'message' => 'Data kriteria tahap 1 OR',
            'data' => $kriteria
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
            'kriteria' => 'required|string',
            'bobot' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $kriteria =  KriteriaTahap2::create($request->all());
            $a = 1;
            $latest = KriteriaTahap2::latest()->first()->id_k1;
            $subk_default = SubKriteriaTahap2::create([
                'id_k2' => $a . $latest,
                'kriteria' => $request->kriteria,
                'sk_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);
            $response = [
                'message' => 'Kriteria created',
                'data' => $kriteria
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
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
