<?php

use App\Http\Controllers\AtalhoController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\ProspeccaoLPRController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControleController;
use App\Http\Controllers\ExemploController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\TelefoneController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

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

// rota para reload do captcha

Route::get('reload-captcha', function(){

    return response()->json(['captcha'=> captcha_img()]);

})->name('reload-captcha');

// rota para reload do captcha


Route::group(['middleware' => ['local.auth','auth','auth2']],function (){


    Route::resource('prospeccoesLPR', ProspeccaoLPRController::class);

    //ROTAS DEFAULT
    Route::get('/', function(){
            return view('home.index');
    })->name('/');

    Route::get('cidades', [CameraController::class,'cidades'])->name('cidades');
    Route::get('cameras/view', [CameraController::class,'view']);
    Route::get('mosaicos', [CameraController::class,'mosaicos'])->name('mosaicos');
    Route::post('atualizaMosaicos', [CameraController::class,'atualizaMosaicos'])->name('atualizaMosaicos');
    Route::resource('cameras', CameraController::class);

    Route::get('onibus', function () {
        $httpOptions = static function (): array {
            $base = ['timeout' => 25, 'connect_timeout' => 10];
            if (app()->environment('local')) {
                return $base;
            }
            $proxy = env('ONIBUS_HTTP_PROXY', 'http://proxy-02.pr.gov.br:8000');

            return array_merge($base, ['proxy' => $proxy]);
        };

        $fetchUrbs = static function (string $path) use ($httpOptions) {
            $url = 'https://transporteservico.urbs.curitiba.pr.gov.br/'.$path.'?c=ea7b9';
            $response = Http::withOptions($httpOptions())->get($url);
            if (! $response->successful()) {
                return null;
            }
            $json = $response->json();
            if (is_array($json)) {
                return $json;
            }
            $decoded = json_decode($response->body(), true);

            return is_array($decoded) ? $decoded : null;
        };

        try {
            $registros = Cache::remember('onibus', 10, function () use ($fetchUrbs) {
                $linhas = Cache::remember('linhas', 60 * 60, function () use ($fetchUrbs) {
                    $data = $fetchUrbs('getLinhas.php');

                    return is_array($data) ? $data : [];
                });

                $registros = $fetchUrbs('getVeiculos.php');
                if (! is_array($registros)) {
                    return [];
                }

                $now = Carbon::now('America/Sao_Paulo');

                foreach ($registros as $prefixo => &$veiculo) {
                    if (! is_array($veiculo)) {
                        continue;
                    }

                    $status = 'desconhecido';
                    if (! empty($veiculo['REFRESH'])) {
                        try {
                            $refreshTime = Carbon::createFromFormat('H:i', $veiculo['REFRESH'], 'America/Sao_Paulo');
                            if ($refreshTime->gt($now)) {
                                $refreshTime->subDay();
                            }
                            $minutesDiff = $now->diffInMinutes($refreshTime);
                            if ($minutesDiff <= 2) {
                                $status = 'online';
                            } elseif ($minutesDiff <= 5) {
                                $status = 'atrasado';
                            } elseif ($minutesDiff <= 10) {
                                $status = 'offline';
                            }
                        } catch (\Throwable $e) {
                            $status = 'desconhecido';
                        }
                    }

                    $codLinha = $veiculo['CODIGOLINHA'] ?? '';
                    if ($codLinha !== '' && $codLinha !== 'REC') {
                        $linha = collect($linhas)->firstWhere('COD', $codLinha);
                        if ($linha) {
                            $veiculo['NOME_LINHA'] = $linha['NOME'] ?? null;
                            $veiculo['CATEGORIA_LINHA'] = $linha['CATEGORIA_SERVICO'] ?? null;
                            $veiculo['COR_LINHA'] = $linha['NOME_COR'] ?? null;
                        }
                    }

                    $veiculo['STATUS'] = $status;
                }
                unset($veiculo);

                return $registros;
            });

            return response()->json($registros);
        } catch (\Throwable $th) {
            report($th);

            return response()->json([]);
        }
    });

});
