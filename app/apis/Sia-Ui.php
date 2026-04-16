<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("Sia-Ui"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
    
    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
