<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class SubKriteria2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kriteria = KriteriaTahap2::get();
        $subkriteria = SubKriteriaTahap2::with('KriteriaTahap2')->get();
        $a = 0;

        foreach ($kriteria as $k) {
            $data[$a]['id_k2'] = $k->id_k2;
            $data[$a]['kode'] = $k->kode;
            $data[$a]['kriteria'] = $k->kriteria;
            $data[$a]['k_sc'] = $k->k_sc;
            $data[$a]['bobot'] = $k->bobot;
            foreach ($subkriteria as $sk) {
                $count = $sk->where('id_k2', $k->id_k2)->get();
                if ($count->count() > 1) {
                    foreach ($count as $i => $c) {
                        $ssk = $sk->where('id_k2', $k->id_k2);
                        $nsk = $ssk->where('id_sk2', $c->id_sk2)->get(['id_sk2', 'sub_kriteria', 'sk_sc', 'kode', 'bobot'])->first();
                        $jsk[$i]['id_sk2'] = $nsk->id_sk2;
                        $jsk[$i]['kode'] = $nsk->kode;
                        $jsk[$i]['sub_kriteria'] = $nsk->sub_kriteria;
                        $jsk[$i]['sk_sc'] = $nsk->sk_sc;
                        $jsk[$i]['bobot'] = $nsk->bobot;
                    }
                    $data[$a]['subkriteria'] = $jsk;
                } elseif ($count->count() == 1) {
                    foreach ($count as $i => $c) {
                        $ssk = $sk->where('id_k2', $k->id_k2);
                        $nsk = $ssk->where('id_sk2', $c->id_sk2)->get(['id_sk2', 'sub_kriteria', 'sk_sc', 'kode', 'bobot'])->first();
                        $data[$a]['id_sk2'] = $nsk->id_sk2;
                        $data[$a]['bobot_sk'] = $nsk->bobot;
                    }
                }
            }
            $a++;
        }
        $response = [
            'message' => 'Data sub-kriteria tahap 1 OR',
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
            'id_k2' => 'required|numeric',
            'sub_kriteria' => 'required|string',
            'kode' => 'required|string',
            'bobot' => 'required|numeric|not_in:0|regex:/^[1-9][0-9]+/',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $detect = SubKriteriaTahap2::where('id_k2', '=', $request->id_k2)->orderBy('id_sk2', 'desc')->first();
            $a = 1;
            if ($detect == null) {
                $subkriteria =  SubKriteriaTahap2::create([
                    'id_sk2' => (int) $request->id_k2 . $a,
                    'id_k2' => $request->id_k2,
                    'sub_kriteria' => $request->sub_kriteria,
                    'kode' => $request->kode,
                    'sk_sc' => Str::snake($request->sub_kriteria),
                    'bobot' => $request->bobot
                ]);
            } else {
                $detect2 = SubKriteriaTahap2::where('id_k2', '=', $request->id_k2)->orderBy('id_sk2', 'desc')->first()->id_sk2;
                $num = $detect2 + $a;
                $subkriteria =  SubKriteriaTahap2::create([
                    'id_sk2' => $num,
                    'id_k2' => $request->id_k2,
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
        $kriteria = SubKriteriaTahap2::with('KriteriaTahap2')->where('id_sk2', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap2->kriteria;
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
        $kriteria = SubKriteriaTahap2::where('id_sk2', '=', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'sub_kriteria' => 'required|string',
            'kode' => 'required|string',
            'bobot' => 'required|numeric|not_in:0|regex:/^[1-9][0-9]+/',
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
        $kriteria = SubKriteriaTahap2::findOrFail($id);

        try {
            $detect = PenilaianTahap2::where('id_sk2', '=', $id)->count();
            if ($detect >= 1) {
                $response = [
                    'message' => 'Subkriteria tidak bisa dihapus karena sedang proses penilaian'
                ];
            } else if ($detect == 0) {
                $kriteria->delete();
                $response = [
                    'message' => 'Subkriteria deleted',
                    'data' => $kriteria
                ];
            }

            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ]);
        }
    }
}
