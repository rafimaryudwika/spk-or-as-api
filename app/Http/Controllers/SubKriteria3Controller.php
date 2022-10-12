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

class SubKriteria3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kriteria_new = KriteriaTahap3::with('SubKriteriaTahap3')->get();
        $data = $kriteria_new->map(function ($item) {
            foreach ($item->SubKriteriaTahap3 as $skk => $skv) {
                if ($item->SubKriteriaTahap3->count() > 1) {
                    $subk[$skk]['id_sk3'] = $skv->id_sk3;
                    $subk[$skk]['kode'] = $skv->kode;
                    $subk[$skk]['sub_kriteria'] = $skv->sub_kriteria;
                    $subk[$skk]['sk_sc'] = $skv->sk_sc;
                    $subk[$skk]['bobot'] = $skv->bobot;
                    $item->subkriteria = $subk;
                } elseif ($item->SubKriteriaTahap3->count() == 1) {
                    $item->id_sk3 = $skv->id_sk3;
                    $item->bobot_sk = $skv->bobot;
                }
            }
            return $item->makeHidden('SubKriteriaTahap3', 'created_at', 'updated_at');
        });
        $response = [
            'message' => 'Data sub-kriteria tahap 3 OR',
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
        $this->authorize('panitia', User::class);

        $validator = Validator::make($request->all(), [
            'id_k3' => 'required|numeric',
            'sub_kriteria' => 'required|string',
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
            $detect = SubKriteriaTahap3::where('id_k3', '=', $request->id_k3)->orderBy('id_sk3', 'desc')->first();
            $a = 1;
            if ($detect == null) {
                $subkriteria =  SubKriteriaTahap3::create([
                    'id_sk3' => (int) $request->id_k3 . $a,
                    'id_k3' => $request->id_k3,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            } else {
                $detect2 = SubKriteriaTahap3::where('id_k3', '=', $request->id_k3)->orderBy('id_sk3', 'desc')->first()->id_sk3;
                $num = $detect2 + $a;
                $subkriteria =  SubKriteriaTahap3::create([
                    'id_sk3' => $num,
                    'id_k3' => $request->id_k3,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            }
            $response = [
                'message' => 'Subkriteria created',
                'data' => $subkriteria
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Create failed: " . $e->getMessage()
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
        $kriteria = SubKriteriaTahap3::with('KriteriaTahap3')->where('id_sk3', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap3->kriteria;
            $data['subkriteria'] = $kriteria->sub_kriteria;
            $data['kode'] = $kriteria->kode;
            $data['bobot'] = $kriteria->bobot;
        }


        $response = [
            'message' => 'Data subkriteria ' . $kriteria->sub_kriteria,
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

        $kriteria = SubKriteriaTahap3::where('id_sk3', '=', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'sub_kriteria' => 'required|string',
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
                'sub_kriteria' => $request->sub_kriteria,
                'kode' => $request->kode,
                'sk_sc' => Str::snake($request->sub_kriteria),
                'bobot' => $request->bobot,

            ]);
            $response = [
                'message' => 'Kriteria created',
                'data' => $kriteria
            ];
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Update failed: " . $e->getMessage()
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

        $kriteria = SubKriteriaTahap3::findOrFail($id);

        try {
            $detect = PenilaianTahap3::where('id_sk3', '=', $id)->count();
            if ($detect >= 1) {
                $response = [
                    'message' => 'Subkriteria tidak bisa dihapus karena sedang proses penilaian'
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
            } else if ($detect == 0) {
                $kriteria->delete();
                $response = [
                    'message' => 'Subkriteria deleted',
                    'data' => $kriteria
                ];
                return response()->json($response, Response::HTTP_OK); //code...
            }
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
