<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap3;
use App\Models\PenilaianTahap3;
use App\Models\SubKriteriaTahap3;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class Kriteria3Controller extends Controller
{
    public function index()
    {
        $kriteria = KriteriaTahap3::get([
            'kriteria_t3.id_k3',
            'kriteria_t3.kode',
            'kriteria_t3.kriteria',
            'kriteria_t3.k_sc',
            'kriteria_t3.bobot'
        ]);

        $response = [
            'message' => 'Data kriteria tahap 3 OR',
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
            $num = KriteriaTahap3::orderBy('id_k3', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->id_k3 + $a;
            }
            $kriteria =  KriteriaTahap3::create([
                'id_k3' => $b,
                'kriteria' => $request->kriteria,
                'kode' => $request->kode,
                'k_sc' => Str::snake($request->kriteria),
                'bobot' => $request->bobot
            ]);

            $latest = KriteriaTahap3::latest()->first()->id_k3;
            $subk_default = SubKriteriaTahap3::create([
                'id_k3' => $latest,
                'id_sk3' => $latest . $a,
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
        $kriteria = KriteriaTahap3::where('id_k3', $id)->get([
            'kriteria_t3.id_k3',
            'kriteria_t3.kode',
            'kriteria_t3.kriteria',
            'kriteria_t3.k_sc',
            'kriteria_t3.bobot'
        ]);

        $response = [
            'message' => 'Data kriteria tahap 3 OR',
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

        $kriteria = KriteriaTahap3::findOrFail($id);

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

        $kriteria = KriteriaTahap3::findOrFail($id);

        try {
            $detect = SubKriteriaTahap3::where('id_k3', $id)->count();
            $detectSubKrit = SubKriteriaTahap3::where('id_k3', $id)->pluck('id_sk3')->first();
            $detectPeserta = PenilaianTahap3::where('id_sk3', '=', $detectSubKrit)->count();
            if ($detect > 1) {
                $response = [
                    'message' => 'Kriteria gagal dihapus karena kriteria tersebut sudah dipakai lebih dari 1 sub-kriteria, mohon hapus sub-kriteria terlebih dahulu',
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
            } elseif ($detectPeserta >= 1) {
                $response = [
                    'message' => 'Kriteria gagal dihapus karena salah satu sub-kriteria sudah dipakai untuk penilaian',
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $detect2 = SubKriteriaTahap3::where('id_k3', $id)->count();
                if ($detect2 == 1) {
                    $subkriteria = SubKriteriaTahap3::where('id_k3', $id)->delete();
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
                return response()->json($response, Response::HTTP_OK); //code...
            }
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
