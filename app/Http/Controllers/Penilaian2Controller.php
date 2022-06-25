<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PesertaTahap2;
use App\Models\KriteriaTahap2;
use App\Models\PenilaianTahap2;
use App\Models\SubKriteriaTahap2;
use Symfony\Component\HttpFoundation\Response;


class Penilaian2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kriteria = KriteriaTahap2::get();
        $nm = PenilaianTahap2::groupBy('nim')->get('nim');

        $peserta1 = PesertaTahap2::with([
            'Pendaftar',
            'Pendaftar.Gender',
            'Pendaftar.Fakultas',
            'Pendaftar.Jurusan',
            'Pendaftar.Fakultas.BidangFakultas'
        ])->get();
        $f = 0;
        foreach ($peserta1 as $p) {
            $peserta[$f]['nim'] = $p->Pendaftar->nim;
            $peserta[$f]['nama'] = $p->Pendaftar->nama;
            $data_p['nama_panggilan'] = $p->Pendaftar->panggilan;
            $data_p['e_mail'] = $p->Pendaftar->email;
            $data_p['nomor_hp'] = $p->Pendaftar->no_hp;
            $data_p['gender'] = $p->Pendaftar->Gender->gender;
            $data_p['tempat_lahir'] = $p->Pendaftar->tempat_lahir;
            $data_p['tanggal_lahir'] = $p->Pendaftar->tgl_lahir;
            $data_p['fakultas'] = $p->Pendaftar->Fakultas->fakultas;
            $data_p['jurusan'] = $p->Pendaftar->Jurusan->jurusan;
            $data_p['bidang_fakultas'] = $p->Pendaftar->Fakultas->BidangFakultas->bidang_fak;
            $data_p['alamat_di_padang'] = $p->Pendaftar->alamat_pdg;
            $peserta[$f]['detail'] = $data_p;
            foreach ($nm as $v) {
                if ($p->nim != $v->nim) {
                    $peserta[$f]['nilai'] = 'nodata';
                } else {
                    foreach ($kriteria as $k) {
                        $match = ['nim' => $v->nim, 'id_k1' => $k->id_k1];
                        $sub_k['kriteria_' . $k->id_k1] = SubKriteriaTahap2::where('id_k1', $k->id_k1)->get('id_sk1');
                        if (count($sub_k['kriteria_' . $k->id_k1]) > 1) { // jika sub-kriteria dalam kriteria lebih dari 1
                            $multi_sub = SubKriteriaTahap2::with('KriteriaTahap1')->where('id_k1', $k->id_k1)->get();
                            foreach ($multi_sub as $jsk) {
                                $match2 = ['nim' => $v->nim, 'id_k1' => $k->id_k1, 'sub_kriteria_t1.id_sk1' => $jsk->id_sk1];
                                $m_sub[Str::snake($jsk->sub_kriteria)] =  PenilaianTahap2::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                    ->where($match2)->pluck('nilai')->first();
                                $nilaii[Str::snake($k->kriteria)] = $m_sub;
                            }
                        } elseif (count($sub_k['kriteria_' . $k->id_k1]) == 1) {
                            $nilaii[Str::snake($k->kriteria)] = PenilaianTahap2::join('sub_kriteria_t1', 'nilai_t1.id_sk1', '=', 'sub_kriteria_t1.id_sk1')
                                ->where($match)->select('nilai')->first()->nilai;
                        }
                    }
                    $peserta[$f]['nilai'] = $nilaii;
                    break;
                }
            }
            $f++;
        }

        $response = [
            'message' => 'Data peserta tahap 1 OR XI',
            'data' => $peserta
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
