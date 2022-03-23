<?php

use App\Http\Controllers\Kriteria1Controller;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\Penilaian1Controller;
use App\Http\Controllers\SubKriteria1Controller;
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

Route::get('/kriteria1', [Kriteria1Controller::class, 'index']);
Route::get('/subkriteria1', [SubKriteria1Controller::class, 'index']);

Route::get('/penilaian1', [Penilaian1Controller::class, 'index']);
Route::get('/penilaian1/show/{nim}', [Penilaian1Controller::class, 'show']);
Route::post('/penilaian1', [Penilaian1Controller::class, 'store']);
Route::put('/penilaian1/{nim}', [Penilaian1Controller::class, 'update']);
Route::get('/penilaian1/peserta', [Penilaian1Controller::class, 'peserta']);

Route::get('/penilaian1/test', [Penilaian1Controller::class, 'test']);
