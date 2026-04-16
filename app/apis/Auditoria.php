<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$api = new Api("Auditoria"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){
    });
    
    Route::post('uploadLog', function() use($api){
           
        return Http::post($api->baseUrl."/api/uploadLog", request()->all());
        
    })->name('uploadLog_'.$api->apiName);

    Route::post('insertRecord', function() use($api){

        return Http::post($api->baseUrl."/api/insertRecord", request()->all());

    })->name('insertRecord_'.$api->apiName);
});

?>
