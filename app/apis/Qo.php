<?php

namespace App\apis;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Session\Session;

$api = new Api("QO"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
        
        Route::get('listarUnidades', function() use($api){
            return $api->get('/api/listarUnidades',request()->all());
        })->name('listarUnidades_'.$api->apiName);

        Route::get('listarUnidadesMeta4', function() use($api){
            return $api->get('/api/listarUnidadesMeta4',request()->all());
        })->name('listarUnidadesMeta4_'.$api->apiName);
    
        Route::get('listarFuncoes', function() use($api){
            return $api->get('/api/listarFuncoes',request()->all());
        })->name('listarFuncoes_'.$api->apiName);
    
        Route::get('listarLocais', function() use($api){
            return $api->get('/api/listarLocais',request()->all());
        })->name('listarLocais_'.$api->apiName);
        
        Route::get('listarSubordinadas', function() use($api){
            $query = request()->all();
            
            if(Session::get('user')->cdopm_pmpr){
                $query = array_merge(request()->all(),[
                    'opm_pmpr_id' => Session::get('user')->opm_pmpr_id
                ]);
            }
            
            return $api->get('/api/listarSubordinadas', $query);
        })->name('listarSubordinadas_'.$api->apiName);

    });
    
    
});

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO

class Qo {

    public static function buscarUnidade($meta4 = null)
    {
        try {
           
            $apiQo = new Api('QO');
            $res = $apiQo->get('/api/listarUnidadesMeta4', [
                "fields" => [
                    "opmPMPR" => "NOME_META4,ABREVIATURA",
                ],
                "filter" => [
                    "META4" => $meta4
                ]
            ]);
            return $res;
        } catch (\Throwable $th) {
            ddd($th);
            return [];//throw $th;
        }

    }

}

?>
