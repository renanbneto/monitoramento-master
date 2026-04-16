<?php

namespace App\apis;

use App\Http\Controllers\EmailExpressoController;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

$apiName = 'Expresso';

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($apiName)->group(function () use($apiName){

    Route::group(['middleware' => ['auth','auth2']],function() use($apiName){

        Route::get('listarEmails', [EmailExpressoController::class,'listarEmails'])->name('listarEmails_'.$apiName);
        Route::post('enviarEmails', [EmailExpressoController::class,'enviarEmails'])->name('enviarEmails_'.$apiName);
        Route::post('moverEmail', [EmailExpressoController::class,'moverEmail'])->name('moverEmail_'.$apiName);
        Route::post('deletarEmail', [EmailExpressoController::class,'deletarEmail'])->name('deletarEmail_'.$apiName);
        Route::get('baixarAnexos', [EmailExpressoController::class,'baixarAnexos'])->name('baixarAnexos_'.$apiName);
        Route::get('buscarEmails', [EmailExpressoController::class,'buscarEmails'])->name('buscarEmails_'.$apiName);
        
    });
    
    
});
// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO


?>
