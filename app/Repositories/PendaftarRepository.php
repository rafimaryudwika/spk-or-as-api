<?php

namespace App\Repositories;

use App\Models\Pendaftar;
use App\Traits\ResponseAPI;
use Exception;

class PendaftarRepository
{
    use ResponseAPI;

    public function getAllData()
    {
        $pendaftar = $pendaftar = Pendaftar::with([
            'Fakultas',
            'Jurusan',
            'Gender',
            'Fakultas.BidangFakultas'
        ])->get();

        return $pendaftar;
    }

    public function getDataByNim($nim)
    {
        return $this->getAllData()->where('nim', $nim)->first();
    }

    public function requestData($data, $nim = null)
    {
        $pendaftar = $nim ? Pendaftar::find($nim) : new Pendaftar;

        if (!$nim && !$pendaftar) throw new Exception('Pendaftar dengan NIM ' . $nim . ' tidak ditemukan', 404);

        $pendaftar->nim = $data['nim'];
        $pendaftar->email = $data['email'];
        $pendaftar->nama = $data['nama'];
        $pendaftar->panggilan = $data['panggilan'];
        $pendaftar->id_g = $data['id_g'];
        $pendaftar->tempat_lahir = $data['tempat_lahir'];
        $pendaftar->tgl_lahir = $data['tgl_lahir'];
        $pendaftar->alamat_pdg = $data['alamat_pdg'];
        $pendaftar->no_hp = $data['no_hp'];
        $pendaftar->id_f = $data['id_f'];
        $pendaftar->id_j = $data['id_j'];

        return $pendaftar->save();
    }
}
