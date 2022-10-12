<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $jumlahPendaftarFakultas;
        // $jumlahPendaftarGender;
        // $jumlahPendaftarNIM;
        // $jumlahPendaftarBidangFak;
        // $trendPendaftaran;

        $chart = Pendaftar::query()
            ->with(['Gender', 'Fakultas', 'Fakultas.BidangFakultas'])
            ->orderBy('id_g')
            ->get()->unique('id_g');
        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $chart
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
