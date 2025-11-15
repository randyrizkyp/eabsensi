<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Models\Dft_pegawai;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/dataPegawai', [ApiController::class, 'dataPegawai']);
Route::post('/ubahAbsen/updateOrang', [ApiController::class, 'updatePegawai']);
Route::get('/testapi', function(){
    $data = Dft_pegawai::all();
    $pegawai = response()->json([
        'data' => $data
    ]);
    return $pegawai;
});

Route::post('/flush999', [ApiController::class, 'flush']);
