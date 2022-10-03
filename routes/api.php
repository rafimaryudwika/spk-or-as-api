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
// //API route for register new user
// Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
// //API route for login user
// Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });
    Route::get('/fakultas', 'FakultasController@index');
    Route::get('/pendaftar', [PendaftarController::class, 'index']);
    Route::post('/pendaftar', [PendaftarController::class, 'store']);
    Route::get('/pendaftar/{nim}', [PendaftarController::class, 'show']);

    Route::apiResource('/kriteria1', Kriteria1Controller::class);
    Route::apiResource('/kriteria2', Kriteria2Controller::class);
    Route::apiResource('/kriteria3', Kriteria3Controller::class);
    Route::apiResource('/subkriteria1', SubKriteria1Controller::class);
    Route::apiResource('/subkriteria2', SubKriteria2Controller::class);
    Route::apiResource('/subkriteria3', SubKriteria3Controller::class);
    Route::apiResource('/infopeserta1', DetailInfoT1Controller::class);

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
    Route::get('/penilaian2/test', 'Penilaian2Controller@test');
    Route::put('/penilaian2/lulus/{nim}', 'Penilaian2Controller@lulus');
    Route::post('/penilaian2/import', 'Penilaian2Controller@import');
    Route::apiResource('/penilaian2', Penilaian2Controller::class)->except([
        'show', 'destroy'
    ]);
    Route::get('/penilaian3/show/{nim}', 'Penilaian3Controller@show');
    Route::get('/penilaian3/show2/{nim}', 'Penilaian3Controller@show2');
    Route::get('/penilaian3/calculate', 'Penilaian3Controller@calculate');
    Route::put('/penilaian3/lulus/{nim}', 'Penilaian3Controller@lulus');
    Route::post('/penilaian3/import', 'Penilaian3Controller@import');
    Route::apiResource('/penilaian3', Penilaian3Controller::class)->except([
        'show', 'destroy'
    ]);
    // // API route for logout user
    // Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
