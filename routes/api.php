<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Webhooks — autenticação HMAC-SHA256 via middleware webhook.hmac
|--------------------------------------------------------------------------
| Header obrigatório: X-Signature-256: hash_hmac('sha256', $body, $secret)
| Header opcional:    X-Timestamp: unix timestamp (proteção anti-replay ±5min)
*/
Route::prefix('webhooks')->group(function () {
    Route::post('viaturas',    [WebhookController::class, 'viaturas'])->middleware('webhook.hmac:viaturas');
    Route::post('radios',      [WebhookController::class, 'radios'])->middleware('webhook.hmac:radios');
    Route::post('ocorrencias', [WebhookController::class, 'ocorrencias'])->middleware('webhook.hmac:ocorrencias');
    Route::post('panico/mpf',  [WebhookController::class, 'panico'])->middleware('webhook.hmac:panico_mpf')->defaults('tipo', 'mpf');
    Route::post('panico/escola', [WebhookController::class, 'panico'])->middleware('webhook.hmac:panico_escola')->defaults('tipo', 'escola');
    Route::post('lpr',         [WebhookController::class, 'lpr'])->middleware('webhook.hmac:lpr');
});


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

Route::get('update',
    function (Request $request, Response $response){
    // verifica se é uma chamada do Sia se não retorna  não autorizado

    // Busca o novo arquivo de
    try{

        $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(3)->get(env('DOMINIO_SIA').':'.env('PORTA_SIA').'/api/update/atualizacao',[
            'token' => 'hgjfhgjkefhjkgherjkgh',
        ]); // Envia o email do usuario para realizar a recuperação

    }catch (\Exception $e){
        // ddd($e);
        return $e->getCode().$e->getMessage();
    }

    Storage::disk('config')->put('atualizacao.php',$retorno); // Atualiza a view de login Local com a view de login recebida do servidor sia

    Artisan::call('cache:clear');
    Artisan::call('config:cache');


    // Grava no disco
    $atualizacoes = config('atualizacao');

    $result = [];

    foreach ($atualizacoes as $key => $value){

        $atualizacao = config('atualizacao.'.$key);

        try{

            $retorno = Http::withOptions(['verify' => env('SIA_SSL_VERIFY')])->retry(3)->timeout(3)->get(env('DOMINIO_SIA').':'.env('PORTA_SIA').'/api/update/'.$key,[
            'token' => 'hgjfhgjkefhjkgherjkgh',
            ]);

            Storage::disk($atualizacao['destino'])->put($atualizacao['arquivo'],$retorno); // Salva no destino o arquivo recebido
            
            array_push($result,"Sucesso ao salvar arquivo ".$atualizacao['arquivo']);

        }catch (\Exception $e){
            array_push($result,"Erro ao salvar arquivo ".$atualizacao['arquivo']);
            //ddd($e);
           // return $e->getCode().$e->getMessage();
        }

    }

    Artisan::call('config:clear');

    return $result;

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
