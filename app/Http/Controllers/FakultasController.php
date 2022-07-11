<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fakultas = Fakultas::with('BidangFakultas')
            ->get();

        $response = [
            'message' => 'Data kriteria tahap 1 OR',
            'data' => $fakultas
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fakultas  $fakultas
     * @return \Illuminate\Http\Response
     */
    public function show(Fakultas $fakultas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fakultas  $fakultas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fakultas $fakultas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fakultas  $fakultas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fakultas $fakultas)
    {
        //
    }
}
