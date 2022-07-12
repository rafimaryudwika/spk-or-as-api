<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
use App\Models\DetailInfoT2;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DetailInfoT2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DetailInfoT2::all();

        $response = [
            'message' => 'Data tipe info',
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
            'nama_info' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $num = DetailInfoT2::orderBy('tipe_info2', 'desc')->first();
            $a = 1;
            if ($num == null) {
                $b = $a;
            } else {
                $b = $num->tipe_info2 + $a;
            }
            $info =  DetailInfoT2::create([
                'tipe_info1' => $b,
                'nama_info' => $request->nama_info,
                'info_sc' => Str::snake($request->nama_info)
            ]);
            $response = [
                'message' => 'Info peserta created',
                'data' => $info
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
     * @param  \App\Models\DetailInfoT2  $detailInfoT2
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DetailInfoT2::findOrFail($id);
        $response = [
            'message' => 'Tabel tipe informasi tahap 2',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailInfoT2  $detailInfoT2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_info' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $update = DetailInfoT2::findOrFail($id)->update([
                'nama_info' => $request->nama_info,
                'info_sc' => Str::snake($request->nama_info)
            ]);
            $response = [
                'message' => 'Info peserta edited',
                'data' => $update
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
     * @param  \App\Models\DetailInfoT2  $detailInfoT2
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = DetailInfoT2::findOrFail($id)->delete();
            $response = [
                'message' => 'Info peserta deleted',
                'data' => $delete
            ];
            return response()->json($response, Response::HTTP_OK); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
