<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Penilaian1Service;

class Penilaian1Controller extends Controller
{
    protected $penilaian1Service;

    public function __construct(Penilaian1Service $penilaian1)
    {
        $this->penilaian1Service = $penilaian1;
    }
    public function index()
    {
        return $this->penilaian1Service->getAllData();
    }

    public function import()
    {
        return $this->penilaian1Service->import();
    }

    public function lulus(Request $request, $id)
    {
        return $this->penilaian1Service->lulus($request, $id);
    }

    public function ratio()
    {
        return $this->penilaian1Service->ratio();
    }

    public function store(Request $request)
    {
        return $this->penilaian1Service->requestData($request);
    }

    public function update(Request $request, $id)
    {
        return $this->penilaian1Service->requestData($request, $id);
    }

    public function show($id)
    {
        return $this->penilaian1Service->getDataByNim($id);
    }
}
