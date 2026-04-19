<?php

use App\Http\Controllers\AtalhoController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MosaicoController;
use App\Http\Controllers\OnibusController;
use App\Http\Controllers\ProspeccaoLPRController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControleController;
use App\Http\Controllers\ExemploController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\TelefoneController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('reload-captcha', function () {
    return response()->json(['captcha' => captcha_img()]);
})->name('reload-captcha');

Route::group(['middleware' => ['local.auth', 'auth', 'auth2']], function () {

    Route::resource('prospeccoesLPR', ProspeccaoLPRController::class);

    Route::get('/', function () {
        return view('home.index');
    })->name('/');

    Route::get('cidades', [CameraController::class, 'cidades'])->name('cidades');
    Route::get('cameras/view', [CameraController::class, 'view']);
    Route::get('cameras/status-json', [CameraController::class, 'statusJson'])->name('cameras.status-json');
    Route::resource('cameras', CameraController::class);

    Route::get('onibus', [OnibusController::class, 'index']);

    Route::resource('eventos', EventoController::class);

    Route::resource('mosaicos', MosaicoController::class)->except(['create', 'edit']);
});
