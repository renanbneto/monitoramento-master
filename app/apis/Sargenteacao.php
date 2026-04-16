<?php

namespace App\apis;

use App\Http\Controllers\NotasController;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

$api = new Api("Sargenteacao"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);

        Route::get('listarEscalasExtrajornadaDisponiveis', function() use($api){
            
            $opm_id = Session::get('user')->opm_id;
            $user_id = Session::get('user')->id;

            if($api->apiName == env('APP_NAME')){ //se partir do proprio sistema redirecionar para controller local
                return redirect()->action([ExtraJornadaController::class,'listarEscalasExtrajornadaDisponiveis',['opm_id' => $opm_id, 'user_id' => $user_id]],request()->all());
            }

            return $api->get("/api/listarEscalasExtrajornadaDisponiveis/{$opm_id}/{$user_id}",request()->all());
            
        })->name('listarEscalasExtrajornadaDisponiveis_'.$api->apiName);
       
        Route::post('cadastrarVoluntarioExtrajornada', function() use($api){
            
            $opm_id = Session::get('user')->opm_id;
            $user_id = Session::get('user')->id;
            $user_rg = Session::get('user')->rg;
            $escala_id = request()->input('escala_id');

            $dados = array_merge(request()->all(),[
                'opm_id' => $opm_id,
                'user_id' => $user_id,
                'user_rg' => $user_rg
            ]);

            if($api->apiName == env('APP_NAME')){ //se partir do proprio sistema redirecionar para controller local
                return redirect()->action([ExtraJornadaController::class,'cadastrarVoluntarioExtrajornada',['opm_id' => $escala_id, 'user_id' => $user_id]],request()->all());
            }

            return $api->post("/api/cadastrarVoluntarioExtrajornada/{$escala_id}/{$user_id}",request()->all());
            
        })->name('cadastrarVoluntarioExtrajornada_'.$api->apiName);

        Route::post('removerVoluntarioExtrajornada/{escala_id}', function($escala_id) use($api){
            
            $opm_id = Session::get('user')->opm_id;
            $user_id = Session::get('user')->id;
            $user_rg = Session::get('user')->rg;
            

            $dados = array_merge(request()->all(),[
                'opm_id' => $opm_id,
                'user_id' => $user_id,
                'user_rg' => $user_rg
            ]);

            if($api->apiName == env('APP_NAME')){ //se partir do proprio sistema redirecionar para controller local
                return redirect()->action([ExtraJornadaController::class,'removerVoluntarioExtrajornada',['escala_id' => $escala_id, 'user_id' => $user_id]],request()->all());
            }

            return $api->post("/api/removerVoluntarioExtrajornada/{$escala_id}/{$user_id}",request()->all());
            
        })->name('removerVoluntarioExtrajornada_'.$api->apiName);
    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
