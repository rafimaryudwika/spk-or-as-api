<?php

namespace App\Http\Controllers;

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
        // //

        $kriteria = KriteriaTahap1::get();
        foreach ($kriteria as $k) {
            $sub_k['kriteria' . $k->id_k1] = SubKriteriaTahap1::where('id_k1', $k->id_k1)->get('id_sk1');
            if (count($sub_k['kriteria' . $k->id_k1]) > 1) {
                $subkriteria1 = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1);
                $list['Kriteria'] = $subkriteria1->kriteria;
                $list['Bobot'] = $subkriteria1->KriteriaTahap1->bobot;
                $multi_sub = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                foreach ($multi_sub as $ms) {
                    $subk['Sub-Kriteria'] = $subkriteria1->sub_kriteria;
                    $subk['Bobot'] = $subkriteria1->bobot;
                }
                $list['Sub-Kriteria'][] = $subk;
            } elseif (count($sub_k['kriteria' . $k->id_k1]) == 1) {
                $subkriteria1 = SubKriteriaTahap1::with('KriteriaTahap1')->where('id_k1', $k->id_k1);
                $list['Kriteria'] = $subkriteria1->KriteriaTahap1->kriteria;
                $list['Bobot'] = $subkriteria1->KriteriaTahap1->bobot;
            }
            $list;
        }
        return $list;

        $response = [
            'message' => 'Data sub-kriteria tahap 1 OR',
            'data' => $list
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
