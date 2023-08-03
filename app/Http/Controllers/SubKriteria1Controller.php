<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SubKriteriaTahap1;
use App\Services\SubKriteria1Service;

class SubKriteria1Controller extends Controller
{
    protected $SubKriteria1Service;
    public function __construct(SubKriteria1Service $subk)
    {
        $this->SubKriteria1Service = $subk;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->SubKriteria1Service->getAllData();
    }

    public function transpose()
    {
        $sub_k = SubKriteriaTahap1::with('KriteriaTahap1')->get();
        $data = $sub_k->map(function ($item) {
            $item->id_sk1 = $item->id_sk1;
            $item->kriteria = $item->KriteriaTahap1->kriteria;
            $item->k_sc = $item->KriteriaTahap1->k_sc;
            return $item->makeHidden('KriteriaTahap1');
        });

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
        $this->authorize('panitia', User::class);
        return $this->SubKriteria1Service->requestData($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->SubKriteria1Service->getDataById($id);
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
        return $this->SubKriteria1Service->requestData($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->SubKriteria1Service->delete($id);
    }
}
