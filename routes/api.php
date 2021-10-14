<?php

use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\Penilaian1Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pendaftar', [PendaftarController::class, 'index']);
Route::post('/pendaftar', [PendaftarController::class, 'store']);
Route::get('/pendaftar/{nim}', [PendaftarController::class, 'show']);

Route::get('/penilaian1', [Penilaian1Controller::class, 'index']);
Route::get('/penilaian1/peserta1', [Penilaian1Controller::class, 'peserta']);
Route::get('/penilaian1/nilai', [Penilaian1Controller::class, 'penilaian1']);
Route::get('/penilaian1/table_sk1', [Penilaian1Controller::class, 'sk1_table']);
Route::get('/penilaian1/table_k1', [Penilaian1Controller::class, 'krit1_table']);
Route::get('/penilaian1/trans', [Penilaian1Controller::class, 'calculate']);
