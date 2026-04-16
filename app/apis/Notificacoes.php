<?php

namespace App\apis;

use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("Notificacoes"); // Instancia a API

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);

        Route::get('listarNotificacoes', function() use($api){
            return $api->get('/api/listarNotificacoes',request()->all());
        })->name('listarNotificacoes_'.$api->apiName);
        
        Route::get('listarNotificacoesHistorico', function() use($api){
            return $api->get('/api/listarNotificacoesHistorico',request()->all());
        })->name('listarNotificacoesHistorico_'.$api->apiName);

        Route::post('adicionarNotificacao', function() use ($api) {
            $response = $api->post('/api/adicionarNotificacao', request()->all());

            // Se for um array, assume erro 500 apenas se tiver um campo 'error'
            if (is_array($response)) {
                if (isset($response['error'])) {
                    return response()->json($response, 500);
                }
                return response()->json($response, 200);
            }

            // Caso contrário, retorna erro genérico
            return response()->json(['error' => 'Resposta inesperada da API', 'detalhes' => $response], 500);
        })->name('adicionarNotificacao_'.$api->apiName);

        Route::post('confirmarLeitura', function() use($api){

            if(request()->input('leituraObrigatoria')){
                session()->get('user')->notificacao_obrigatoria = intval(session()->get('user')->notificacao_obrigatoria -1,10);
            }

            // adicionando o user_id no payload
            $payload = array_merge(['user_id' => session()->get('user')->id],request()->all());

            return $api->post('/api/confirmarLeitura', $payload);
        })->name('confirmarLeitura_'.$api->apiName);

    });


});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
