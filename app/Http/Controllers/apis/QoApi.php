<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

$api = new Api("QO"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
        
        Route::get('listarUnidades', function() use($api){
            $query = request()->all();
            
            if(Session::get('user')->cdopm_pmpr){
                $query = array_merge(request()->all(),[
                    'cdopm_pmpr' => Session::get('user')->cdopm_pmpr
                ]);
            }
            
            return $api->get('/api/listarUnidades', $query);
        })->name('listarUnidades_'.$api->apiName);
    
        Route::get('listarFuncoes', function() use($api){
            return $api->get('/api/listarFuncoes',request()->all());
        })->name('listarFuncoes_'.$api->apiName);
    
        Route::get('listarLocais', function() use($api){
            return $api->get('/api/listarLocais',request()->all());
        })->name('listarLocais_'.$api->apiName);

        Route::get('obterSubordinadas/{id}', function($id) use($api){
            return $api->get('/api/obterSubordinadas/{id}',request()->all());
        })->name('obterSubordinadas_'.$api->apiName);
    
        Route::get('obterSubordinadasM4/{meta4}', function() use($api){
            return $api->get('/api/obterSubordinadasM4/{meta4}',request()->all());
        })->name('obterSubordinadasM4_'.$api->apiName);

    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
