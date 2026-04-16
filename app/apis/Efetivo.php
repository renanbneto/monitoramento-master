<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("Efetivo"); // Instancia a API

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);

        Route::get('listarPoliciais', function() use($api){

            return $api->get('/api/listarPoliciais',request()->all(),["timeout"=>30]);

        })->name('listarPoliciais_'.$api->apiName);

    });

    Route::get('fotos/{cpf}', function($cpf) use($api){

        $dados = [];

        if(!session()->get('user')){
            return response('',404);
        }

        $cpfAcesso = session()->get('user')->cpf && session()->get('user')->cpf != '' ? session()->get('user')->cpf : null;

        if($cpfAcesso) {
            $dados['cpf'] = $cpfAcesso;
        }


        $request = $api->getRaw("/api/fotos/".$cpf,array_merge(request()->all(),$dados));
        $body = $request->getBody();

        return response()->stream(function() use($body) {
          echo $body;
        }, 200,$request->headers());

    })->name('fotos_'.$api->apiName);
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO

?>
