<?php

namespace App\apis;

use App\Http\Controllers\NotasController;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


$api = new Api("Notas"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);

        Route::get('listarNotas', function() use($api){
            
            if($api->apiName == env('APP_NAME')){ //se partir do proprio sistema redirecionar para controller local
                return redirect()->action([NotasController::class,'listarNotas'],request()->all());
            }

            return $api->get('/api/listarNotas',request()->all());
        })->name('listarNotas_'.$api->apiName);
        
        Route::get('proxyIntranet/{url}', function($url) use($api){
            
            /* if($api->apiName == env('APP_NAME')){ //se partir do proprio sistema redirecionar para controller local
                return redirect()->action([NotasController::class,"proxyIntranet"],$url);
            } */

            $baseUrl = config('sistemas.'.$api->apiName.'.appUrl').':'.config('sistemas.'.$api->apiName.'.porta');
            $request = Http::get($baseUrl."/proxy/".$url);
            $body = $request->getBody();

            return response()->stream(function() use($body) {
              echo $body;
            }, 200, $request->getHeaders()); 

            //return $api->get('/proxy/'.$url,request()->all());
        })->name('proxyIntranet_'.$api->apiName);
        
    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
