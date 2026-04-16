<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("Sia-Auth"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
    
        Route::any('listarUsuarios/{busca}', function($busca) use($api){
            return $api->post('/api/listarUsuarios/'.$busca,request()->all());
        })->name('listarUsuarios_'.$api->apiName);

        Route::any('listarIds', function() use($api){
            return $api->post('/api/listarIds',request()->all());
        })->name('listarIds_'.$api->apiName);

        Route::any('desvincularUnidade', function() use($api){
            return $api->get('/api/desvincularUnidade',request()->all());
        })->name('desvincularUnidade_'.$api->apiName);

        Route::any('listarSistemas', function() use($api){
            return $api->get('/api/listarSistemas',request()->all());
        })->name('listarSistemas_'.$api->apiName);

    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
