<?php

use App\Http\Controllers\PendaftarController;
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
Route::get('/fakultas', 'FakultasController@index');
Route::get('/pendaftar', [PendaftarController::class, 'index']);
Route::post('/pendaftar', [PendaftarController::class, 'store']);
Route::get('/pendaftar/{nim}', [PendaftarController::class, 'show']);

// Route::get('/kriteria1', [Kriteria1Controller::class, 'index']);
// Route::get('/subkriteria1', [SubKriteria1Controller::class, 'index']);
Route::apiResource('/kriteria1', Kriteria1Controller::class);
Route::apiResource('/kriteria2', Kriteria2Controller::class);
Route::apiResource('/subkriteria1', SubKriteria1Controller::class);
Route::apiResource('/subkriteria2', SubKriteria2Controller::class);

Route::get('/penilaian1/show/{nim}', 'Penilaian1Controller@show');
Route::get('/penilaian1/show2/{nim}', 'Penilaian1Controller@show2');
Route::get('/penilaian1/calculate', 'Penilaian1Controller@calculate');
Route::put('/penilaian1/lulus/{nim}', 'Penilaian1Controller@lulus');
Route::get('/penilaian1/test', 'Penilaian1Controller@test');
Route::apiResource('/penilaian1', Penilaian1Controller::class)->except([
    'show', 'destroy'
]);
Route::get('/penilaian2/show/{nim}', 'Penilaian2Controller@show');
Route::get('/penilaian2/show2/{nim}', 'Penilaian2Controller@show2');
Route::get('/penilaian2/calculate', 'Penilaian2Controller@calculate');
Route::put('/penilaian2/lulus/{nim}', 'Penilaian2Controller@lulus');
Route::post('/penilaian2/import', 'Penilaian2Controller@import');
Route::apiResource('/penilaian2', Penilaian2Controller::class)->except([
    'show', 'destroy'
]);
