<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap1;
use App\Models\SubKriteriaTahap1;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class Kriteria1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kriteria = KriteriaTahap1::get([
            'kriteria_t1.id_k1',
            'kriteria_t1.kriteria',
            'kriteria_t1.k_sc',
            'kriteria_t1.bobot'
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
            'kode' => 'required|string',
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
            $num = KriteriaTahap1::orderBy('id_k1', 'desc')->first();
            $a = 1;
            $kriteria =  KriteriaTahap1::create([
                'id_k1' => $num->id_k1 + $a,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap1::latest()->first()->id_k1;
            $subk_default = SubKriteriaTahap1::create([
                'id_k1' => $latest,
                'id_sk1' => $latest . $a,
                'sub_kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);
            $response = [
                'message' => 'Kriteria created',
                'data' => $kriteria . $subk_default
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $kriteria = KriteriaTahap1::where('id_k1', $id)->get([
            'kriteria_t1.id_k1',
            'kriteria_t1.kode',
            'kriteria_t1.kriteria',
            'kriteria_t1.k_sc',
            'kriteria_t1.bobot'
        ]);

        $response = [
            'message' => 'Data kriteria tahap 1 OR',
            'data' => $kriteria
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
        $kriteria = KriteriaTahap1::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kriteria' => 'required|string',
            'kode' => 'required|string',
            'bobot' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $kriteria->update([
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot,

            ]);
            $response = [
                'message' => 'Kriteria created',
                'data' => $kriteria
            ];
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kriteria = KriteriaTahap1::findOrFail($id);

        try {
            $detect = SubKriteriaTahap1::where('id_k1', $id)->count();
            if ($detect > 1) {
                $response = [
                    'message' => 'Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu',
                ];
            } else {
                $detect2 = SubKriteriaTahap1::where('id_k1', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap1::where('id_k1', $id)->delete();
                    $kriteria->delete();
                    $response = [
                        'message' => 'Kriteria and its subkriteria deleted',
                        'data' => $subkriteria . ' , ' . $kriteria
                    ];
                } else {
                    $kriteria->delete();
                    $response = [
                        'message' => 'Kriteria deleted',
                        'data' => $kriteria
                    ];
                }
            }
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ]);
        }
    }
}
