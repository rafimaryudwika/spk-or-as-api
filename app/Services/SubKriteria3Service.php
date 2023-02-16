<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\SubKriteria3Repository;
use Illuminate\Support\Facades\Validator;

class SubKriteria3Service
{
    use ResponseAPI;
    protected $subKriteria3;

    public function __construct(SubKriteria3Repository $subKriteria3)
    {
        $this->subKriteria3 = $subKriteria3;
    }

    public function getAllData()
    {
        try {
            $subKriteria = $this->subKriteria3->getAllData();
            return $this->success('List subkriteria', $subKriteria, 200);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataById($id)
    {
        try {
            $subKriteria = $this->subKriteria3->getDataById($id);
            return $this->success('List subkriteria', $subKriteria, 200);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_k3' => 'sometimes|required|numeric',
                'sub_kriteria' => 'required|string',
                'kode' => 'required|string',
                'bobot' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $subKriteria = $this->subKriteria3->requestData($request, $id);
            return $this->success($id ? 'Subkriteria updated' : 'Subkriteria created', $subKriteria, $id ? 200 : 201);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function delete($id)
    {
        try {
            $subKriteria = $this->subKriteria3->delete($id);
            return $this->success('Subkriteria deleted', $subKriteria, 200);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
