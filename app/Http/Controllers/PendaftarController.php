<?php

namespace App\Http\Controllers;

use App\Services\PendaftarService;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    protected $pendaftarService;

    public function __construct(PendaftarService $pendaftar)
    {
        $this->pendaftarService = $pendaftar;
    }

    public function index()
    {
        return $this->pendaftarService->getAllData();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->pendaftarService->requestData($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->pendaftarService->getDataById($id);
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
        return $this->pendaftarService->requestData($request, $id);
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
