<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\BidangFakultas;
use Illuminate\Support\Facades\DB;
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
        $gender = Pendaftar::query()
            ->with(['Gender'])->select('id_g', DB::raw('count(*) as total'))
            ->groupBy('id_g')
            ->get();

        $fakultas = Pendaftar::query()
            ->with(['Fakultas'])->select('id_f', DB::raw('count(*) as total'))
            ->groupBy('id_f')
            ->get();

        $bp = Pendaftar::query()
            ->select(DB::raw('concat("20", substr(CONVERT(nim, CHAR), 1, 2)) as bp, count(*) as total'))
            ->groupBy('bp')
            ->get();

        $date = Pendaftar::query()
            ->select(DB::raw('Date(tgl_daftar) as date, COUNT(*) as "total"'),)
            ->orderBy('date')
            ->groupBy('date')
            ->get();

        $bidang_fak = BidangFakultas::with('Fakultas.Pendaftar')->get()
            ->map(function ($bidangFakultas) {
                return [
                    'bidang_fak' => $bidangFakultas->bidang_fak,
                    'total' => $bidangFakultas->n_pendaftar,
                ];
            });

        $chart['gender'] = $gender;
        $chart['fakultas'] = $fakultas;
        $chart['bp'] = $bp;
        $chart['bidang_fakultas'] = $bidang_fak;
        $chart['tgl_daftar'] = $date;
        $response = [
            'message' => 'Data chart',
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
