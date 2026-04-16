<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("P4"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
        
        Route::get('listarAutomoveis', function() use($api){
            return $api->get('/api/listarAutomoveis',request()->all());
        })->name('listarAutomoveis_'.$api->apiName);
        
        Route::put('atualizarAutomovel', function() use($api){
            return $api->put('/api/atualizarAutomovel',request()->all());
        })->name('atualizarAutomovel_'.$api->apiName);
        
        Route::get('listarMotocicletas', function() use($api){
            return $api->get('/api/listarMotocicletas',request()->all());
        })->name('listarMotocicletas_'.$api->apiName);
        
        Route::get('listarEmbarcacoes', function() use($api){
            return $api->get('/api/listarEmbarcacoes',request()->all());
        })->name('listarEmbarcacoes_'.$api->apiName);
        
        Route::get('listarAeronaves', function() use($api){
            return $api->get('/api/listarAeronaves',request()->all());
        })->name('listarAeronaves_'.$api->apiName);
        
        Route::get('listarEquinos', function() use($api){
            return $api->get('/api/listarEquinos',request()->all());
        })->name('listarEquinos_'.$api->apiName);
        
        Route::get('listarCaes', function() use($api){
            return $api->get('/api/listarCaes',request()->all());
        })->name('listarCaes_'.$api->apiName);
        
        Route::get('listarRecursos', function() use($api){
            return $api->get('/api/listarRecursos',request()->all());
        })->name('listarRecursos_'.$api->apiName);
        
    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
