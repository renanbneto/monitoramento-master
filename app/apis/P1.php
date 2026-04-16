<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("P1"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);

        Route::get('listarPoliciais', function() use($api){

            return $api->get('/api/listarPoliciais',request()->all());
            
        })->name('listarPoliciais_'.$api->apiName);
        
    });
    
    Route::get('fotos/{rg}', function($rg) use($api){
           
        $request = Http::get($api->baseUrl."/api/fotos/".$rg);
        $body = $request->getBody();

        return response()->stream(function() use($body) {
          echo $body;
        }, 200,$request->headers()); 
        
    })->name('fotos_'.$api->apiName);
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO

?>
