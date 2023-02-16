<?php

namespace App\Services;

use Exception;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\PendaftarRepository;
use Illuminate\Support\Facades\Validator;

class PendaftarService
{
    use ResponseAPI;
    protected $pendaftar;
    public function __construct(PendaftarRepository $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    public function transpose($data)
    {
        foreach ($data as $i => $p) {
            $data[$i]['tgl_daftar'] = $p->tgl_daftar;
            $data[$i]['nim'] = $p->nim;
            $data[$i]['nama'] = $p->nama;
            $data[$i]['panggilan'] = $p->panggilan;
            $data[$i]['gender'] = $p->Gender->gender;
            $data[$i]['tempat_lahir'] = $p->tempat_lahir;
            $data[$i]['tgl_lahir'] = $p->tgl_lahir;
            $data[$i]['alamat_pdg'] = $p->alamat_pdg;
            $data[$i]['email'] = $p->email;
            $data[$i]['no_hp'] = $p->no_hp;
            $data[$i]['fakultas'] = $p->Fakultas->fakultas;
            $data[$i]['jurusan'] = $p->Jurusan->jurusan;
            $data[$i]['bidang_fak'] = $p->Fakultas->BidangFakultas->bidang_fak;
            $data[$i]['daftar_ulang'] = $p->daftar_ulang;
        }

        return $data;
    }
    public function getAllData()
    {
        try {
            $data = $this->pendaftar->getAllData();
            $pendaftar = $this->transpose($data);
            return $this->success("Data Pendaftar OR XI", $pendaftar);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDataById($id)
    {
        try {
            $data = $this->pendaftar->getDataByNim($id);
            $pendaftar = $this->transpose($data);
            return $this->success("Data salah satu pendaftar OR XI", $pendaftar);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestData(Request $request, $id = null)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nim' => ['required|sometimes|numeric'],
                    'email' => ['required'],
                    'nama' => ['required'],
                    'panggilan' => ['required'],
                    'id_g' => ['required'],
                    'tempat_lahir' => ['required'],
                    'tgl_lahir' => ['required', 'date'],
                    'alamat_pdg' => ['required'],
                    'no_hp' => ['required', 'numeric'],
                    'id_f' => ['required'],
                    'id_j' => ['required']
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $pendaftar = $this->pendaftar->requestData($request, $id);
            return $this->success($id ? 'Kriteria updated' : "Kriteria created", $pendaftar, $id ? 200 : 201);
        } catch (Exception $e) {
            if (!$e->getCode() || $e->getCode() === 0) return response()->json($e->__toString(), 500);
            return $this->error("Request data gagal!" . $e->getMessage(), $e->getCode());
        }
    }

    public function delete($id)
    {
        // return $this->pendaftar->deleteData($id);
    }
}
