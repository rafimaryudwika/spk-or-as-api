<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Kriteria2Repository;
use Illuminate\Support\Facades\Validator;

class Kriteria2Service
{
    use ResponseAPI;
    protected $kriteria;
    public function __construct(Kriteria2Repository $kriteria)
    {
        $this->kriteria = $kriteria;
    }

    public function getAllData()
    {
        try {
            $kriteria = $this->kriteria->getAllData();
            return $this->success("Data tahap 2 OR XI", $kriteria);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataById($id)
    {
        try {
            $kriteria = $this->kriteria->getDataById($id);
            return $this->success("Data salah satu kriteria tahap 2 OR XI", $kriteria);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|string',
                'kriteria' => 'required|string',
                'bobot' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $kriteria = $this->kriteria->requestData($request, $id);
            return $this->success($id ? 'Kriteria updated' : "Kriteria created", $kriteria, $id ? 200 : 201);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error("Request data gagal!" . $e->getMessage(), $e->getCode());
        }
    }

    public function delete($id)
    {
        return $this->kriteria->deleteData($id);
    }
}
