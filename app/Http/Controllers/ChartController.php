<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
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
        function getRatio($array)
        {
            if (count($array) === 2) {
                foreach ($array as $k => $v) {
                    $data[$k] = $v['total'];
                }
            } else {
                $response = ['message' => "Arraynya kurang atau lebih dari 2"];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $min = min([$data[0], $data[1]]);
            $max = max([$data[0], $data[1]]);
            $count = fdiv($max, $min);
            if ($data[0] > $data[1]) {
                $ratio = round($count, 2) . ':1';
            } else {
                $ratio = '1:' . round($count, 2);
            }
            $percent = round($count * 100, 2);
            return [
                'ratio' => "$ratio",
                'persentase' => "$percent" . ' %',
            ];
        }

        $gender = Pendaftar::query()
            ->with(['Gender'])->select('id_g', DB::raw('count(*) as total'))
            ->groupBy('id_g')
            ->get();

        $fakultas = Fakultas::get();

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
            })->toArray();

        $chart['gender'] = $gender;
        $chart['gender_ratio'] = getRatio($gender);
        $chart['fakultas'] = $fakultas;
        $chart['bp'] = $bp;
        $chart['bp_ratio'] = getRatio($bp);
        $chart['bidang_fakultas'] = $bidang_fak;
        $chart['bidang_fakultas_ratio'] = getRatio($bidang_fak);
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
