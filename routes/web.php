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


Route::group(['middleware' => ['auth','auth2']],function (){


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

    Route::get('onibus', function(){
        try {

            /* $response = Http::withOptions([
                'proxy' => 'http://proxy-02.pr.gov.br:8000'
            ])->get('https://transporteservico.urbs.curitiba.pr.gov.br/getVeiculos.php?c=ea7b9');
            return $response->json(); */

            return Cache::remember('onibus', 10, function () {


                $linhas =  Cache::remember('linhas', 60*60, function () {
                
                    $response = Http::withOptions([
                        'proxy' => 'http://proxy-02.pr.gov.br:8000'
                    ])->get('https://transporteservico.urbs.curitiba.pr.gov.br/getLinhas.php?c=ea7b9');
                    
                    return $response->json();
                    
                });

                $response = Http::withOptions([
                    'proxy' => 'http://proxy-02.pr.gov.br:8000'
                ])->get('https://transporteservico.urbs.curitiba.pr.gov.br/getVeiculos.php?c=ea7b9');
                $registros = json_decode($response->body(),true);
                
                $now = Carbon::now('America/Sao_Paulo');
                
                $onibus = new \stdClass();

                foreach ($registros as $prefixo => &$veiculo) {
                    
                    // Obtém a hora de "refresh" no formato H:i
                    $refreshTime = Carbon::createFromFormat('H:i', $veiculo["REFRESH"],'America/Sao_Paulo');

                    // Ajuste para o caso onde a hora de refresh é maior que a hora atual (indicando data anterior)
                    if ($refreshTime->gt($now)) {
                        // Subtrai um dia, pois o refresh é do dia anterior
                        $refreshTime->subDay();
                    }
                    
                    // Calcula a diferença em minutos
                    $minutesDiff = $now->diffInMinutes($refreshTime);

                    
                    // Define o status com base na diferença de tempo
                    if ($minutesDiff <= 2) {
                        $status = 'online';
                    } elseif ($minutesDiff <= 5) {
                        $status = 'atrasado';
                    } elseif ($minutesDiff <= 10) {
                        $status = 'offline';
                    } else {
                        $status = 'desconhecido';
                    }

                    if($veiculo["CODIGOLINHA"] != '' && $veiculo["CODIGOLINHA"] != 'REC'){

                        $linha = collect($linhas)->firstWhere('COD', $veiculo["CODIGOLINHA"]);
    
                        if($linha){
                            $veiculo["NOME_LINHA"] = $linha["NOME"];
                            $veiculo["CATEGORIA_LINHA"] = $linha["CATEGORIA_SERVICO"];
                            $veiculo["COR_LINHA"] = $linha["NOME_COR"];
                        }
                    }
                    
                    
                    // Adiciona o campo "status" ao veículo
                    $veiculo["STATUS"] = $status;

                }

                return json_encode($registros);
            });

            
        } catch (\Throwable $th) {
            return response($th->getMessage(),500);
        }
    });

});
