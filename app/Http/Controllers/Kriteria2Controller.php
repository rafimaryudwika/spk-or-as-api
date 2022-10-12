<?php

namespace App\Http\Controllers;

use Throwable;
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
        $this->authorize('panitia', User::class);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string',
            'kriteria' => 'required|string',
            'bobot' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $num = KriteriaTahap2::orderBy('id_k2', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->id_k2 + $a;
            }
            $kriteria =  KriteriaTahap2::create([
                'id_k2' => $b,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap2::latest()->first()->id_k2;
            $subk_default = SubKriteriaTahap2::create([
                'id_k2' => $latest,
                'id_sk2' => $latest . $a,
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
        $kriteria = KriteriaTahap2::where('id_k2', $id)->get([
            'kriteria_t2.id_k2',
            'kriteria_t2.kode',
            'kriteria_t2.kriteria',
            'kriteria_t2.k_sc',
            'kriteria_t2.bobot'
        ]);

        $response = [
            'message' => 'Data kriteria tahap 2 OR',
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
        $this->authorize('panitia', User::class);

        $kriteria = KriteriaTahap2::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kriteria' => 'required|string',
            'kode' => 'required|string',
            'bobot' => 'required|numeric|min:0',
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
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $this->authorize('panitia', User::class);

        $kriteria = KriteriaTahap2::findOrFail($id);

        try {
            $detect = SubKriteriaTahap2::where('id_k2', $id)->count();
            if ($detect > 1) {
                $response = [
                    'message' => 'Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu',
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $detect2 = SubKriteriaTahap2::where('id_k2', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap2::where('id_k2', $id)->delete();
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
                return response()->json($response, Response::HTTP_OK);
            }
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
