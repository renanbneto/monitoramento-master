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

    // Rotas públicas para todos os operadores autenticados
    Route::get('/', function () { return view('home.index'); })->name('/');
    Route::get('cameras/status-json', [CameraController::class, 'statusJson'])->name('cameras.status-json');
    Route::get('cameras/view', [CameraController::class, 'view']);
    Route::get('cidades', [CameraController::class, 'cidades'])->name('cidades');
    Route::get('mosaicos-legacy', [CameraController::class, 'mosaicos'])->name('mosaicos.legacy');
    Route::post('atualizaMosaicos', [CameraController::class, 'atualizaMosaicos'])->name('atualizaMosaicos');
    Route::get('onibus', [OnibusController::class, 'index']);
    Route::get('eventos-count', [EventoController::class, 'count'])->name('eventos.count');
    Route::get('eventos-ativos', [EventoController::class, 'jsonAtivos'])->name('eventos.ativos');

    // Mosaicos — todos os operadores (cada um vê apenas os seus, controller verifica)
    Route::resource('mosaicos', MosaicoController::class);

    // Eventos — todos os operadores podem ver/criar; somente Administrador edita/remove
    Route::resource('eventos', EventoController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('eventos', EventoController::class)->only(['edit', 'update', 'destroy'])
         ->middleware('autorizacao:Administrador');

    // Câmeras — somente Administrador pode criar/editar/remover
    Route::get('cameras/{camera}', [CameraController::class, 'show'])->name('cameras.show');
    Route::resource('cameras', CameraController::class)->except(['show'])
         ->middleware('autorizacao:Administrador');

    // Prospecção LPR — todos os operadores autenticados
    Route::resource('prospeccoesLPR', ProspeccaoLPRController::class);
});
