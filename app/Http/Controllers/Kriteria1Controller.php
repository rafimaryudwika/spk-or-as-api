<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Kriteria1Service;

class Kriteria1Controller extends Controller
{
    protected $kriteria1Service;

    public function __construct(Kriteria1Service $krit)
    {
        $this->kriteria1Service = $krit;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->kriteria1Service->getAllData();
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
        return $this->kriteria1Service->requestData($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->kriteria1Service->getDataById($id);
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
        $this->authorize('panitia', User::class);
        return $this->kriteria1Service->requestData($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->kriteria1Service->delete($id);
    }
}
