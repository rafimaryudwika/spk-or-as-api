<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\KriteriaTahap1;
use App\Models\PenilaianTahap1;
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
            $data[$a]['kriteria'] = $k->kriteria;
            $data[$a]['krit_sc'] = Str::snake($k->kriteria);
            $data[$a]['bobot'] = $k->bobot;
            foreach ($subkriteria as $sk) {
                $count = $sk->where('id_k1', $k->id_k1)->count();
                if ($count > 1) {
                    $ssk = $sk->where('id_k1', $k->id_k1);
                    $nsk = $ssk->get('sub_kriteria')->first();
                    $subk['id_sk1'] = $ssk->get('id_sk1');
                    $subk['sub_kriteria'] = $ssk->get('sub_kriteria')->first();
                    $subk['sk_sc'] = Str::snake($nsk);
                    $subk['bobot'] = $ssk->get('bobot');
                    $data[$a]['subkriteria'] = $ssk->get(['id_sk1', 'sub_kriteria', 'bobot']);
                }
            }
            $a++;
        }
        // foreach ($subkriteria as $sk) {
        //     $data[$a]['Kriteria'] = $sk->KriteriaTahap1->kriteria;
        //     $data[$a]['Subkriteria'] = $sk->sub_kriteria;
        //     $data[$a]['Bobot'] = $sk->bobot;
        //     $a++;
        // }
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
            'kriteria' => 'required|string',
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
