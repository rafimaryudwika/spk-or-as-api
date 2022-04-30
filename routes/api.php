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

Route::get('/pendaftar', [PendaftarController::class, 'index']);
Route::post('/pendaftar', [PendaftarController::class, 'store']);
Route::get('/pendaftar/{nim}', [PendaftarController::class, 'show']);

// Route::get('/kriteria1', [Kriteria1Controller::class, 'index']);
// Route::get('/subkriteria1', [SubKriteria1Controller::class, 'index']);
Route::apiResource('/kriteria1', Kriteria1Controller::class)->except([
    'show'
]);
Route::apiResource('/subkriteria1', SubKriteria1Controller::class)->except([
    'show'
]);

Route::get('/penilaian1/show/{nim}', 'Penilaian1Controller@show');
Route::get('/penilaian1/calculate', 'Penilaian1Controller@calculate');
Route::get('/penilaian1/test', 'Penilaian1Controller@test');
Route::apiResource('/penilaian1', Penilaian1Controller::class)->except([
    'show', 'destroy'
]);
