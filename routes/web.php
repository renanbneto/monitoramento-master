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
            $cfg = config('transporte_urbs');
            $base = [
                'timeout' => $cfg['http']['timeout'],
                'connect_timeout' => $cfg['http']['connect_timeout'],
            ];
            $useProxy = ! app()->environment('local') || ($cfg['proxy']['use_in_local'] ?? false);
            $proxyUrl = $cfg['proxy']['url'] ?? null;
            if ($useProxy && is_string($proxyUrl) && $proxyUrl !== '') {
                return array_merge($base, ['proxy' => $proxyUrl]);
            }

            return $base;
        };

        $fetchUrbs = static function (string $path) use ($httpOptions) {
            $cfg = config('transporte_urbs');
            $base = $cfg['base_url'];
            $code = $cfg['access_code'];
            $url = $base.'/'.$path.'?c='.urlencode((string) $code);
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
            $onibusTtl = max(1, (int) config('transporte_urbs.cache.onibus_ttl'));
            $linhasTtl = max(1, (int) config('transporte_urbs.cache.linhas_ttl'));
            $epLinhas = config('transporte_urbs.endpoints.linhas');
            $epVeiculos = config('transporte_urbs.endpoints.veiculos');

            $registros = Cache::remember('urbs_onibus', $onibusTtl, function () use ($fetchUrbs, $linhasTtl, $epLinhas, $epVeiculos) {
                $linhas = Cache::remember('urbs_linhas', $linhasTtl, function () use ($fetchUrbs, $epLinhas) {
                    $data = $fetchUrbs($epLinhas);

                    return is_array($data) ? $data : [];
                });

                $registros = $fetchUrbs($epVeiculos);
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
