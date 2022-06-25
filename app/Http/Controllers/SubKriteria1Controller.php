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

class SubKriteria1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kriteria = KriteriaTahap1::get();
        $subkriteria = SubKriteriaTahap1::with('KriteriaTahap1')->get();
        $a = 0;

        foreach ($kriteria as $k) {
            $data[$a]['id_k1'] = $k->id_k1;
            $data[$a]['kode'] = $k->kode;
            $data[$a]['kriteria'] = $k->kriteria;
            $data[$a]['k_sc'] = Str::snake($k->kriteria);
            $data[$a]['bobot'] = $k->bobot;
            foreach ($subkriteria as $sk) {
                $count = $sk->where('id_k1', $k->id_k1)->get();
                if ($count->count() > 1) {
                    foreach ($count as $i => $c) {
                        $ssk = $sk->where('id_k1', $k->id_k1);
                        $nsk = $ssk->where('id_sk1', $c->id_sk1)->get(['id_sk1', 'sub_kriteria', 'sk_sc', 'kode', 'bobot'])->first();
                        $jsk[$i]['id_sk1'] = $nsk->id_sk1;
                        $jsk[$i]['kode'] = $nsk->kode;
                        $jsk[$i]['sub_kriteria'] = $nsk->sub_kriteria;
                        $jsk[$i]['sk_sc'] = $nsk->sk_sc;
                        $jsk[$i]['bobot'] = $nsk->bobot;
                    }
                    $data[$a]['subkriteria'] = $jsk;
                } elseif ($count->count() == 1) {
                    foreach ($count as $i => $c) {
                        $ssk = $sk->where('id_k1', $k->id_k1);
                        $nsk = $ssk->where('id_sk1', $c->id_sk1)->get(['id_sk1', 'sub_kriteria', 'sk_sc', 'kode', 'bobot'])->first();
                        $data[$a]['id_sk1'] = $nsk->id_sk1;
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
            'id_k1' => 'required|numeric',
            'kode' => 'required|string',
            'sub_kriteria' => 'required|string',
            'bobot' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $subkriteria =  SubKriteriaTahap1::create($request->all());
            $response = [
                'message' => 'Subkriteria created',
                'data' => $subkriteria
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Create failed: " . $e->getMessage()
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
        $kriteria = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_sk1', $id)->get();

        foreach ($kriteria as $kriteria) {
            $data['kriteria'] = $kriteria->KriteriaTahap1->kriteria;
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
        $kriteria = SubKriteriaTahap1::where('nim', '=', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string',
            'subkriteria' => 'required|string',
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
                'kode' => $request->kode,
                'kriteria' => $request->subkriteria,
                'sk_sc' => Str::snake($request->subkriteria),
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
        $kriteria = SubKriteriaTahap1::findOrFail($id);

        try {
            $kriteria->delete();
            $response = [
                'message' => 'Subkriteria deleted',
                'data' => $kriteria
            ];
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ]);
        }
    }
}
