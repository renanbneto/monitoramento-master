<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
//lista os serviços disponiveis /servicos/listar
Route::get('listar',function(){
    $rotas = array();
    foreach (Route::getRoutes() as $value) {
        //ddd($value);
        if(Str::contains($value->uri,'servicos')){
            array_push($rotas,[
                'metodos' => implode('/',$value->methods),
                'rota' => '/'.$value->uri,
                'nome' => $value->getName(),
                'urlBlade' => 'route(\''.$value->getName().'\')',
                'urlHtml' => env('APP_URL').'/'.$value->uri,
            ]);
        }
    }
    return $rotas;
})->name('listarApis');




//Adicionar Controller de Serviço externos
if(File::exists(app_path() . '/apis/P1.php')){
    require_once(app_path() . '/apis/P1.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Qo.php')){
    require_once(app_path() . '/apis/Qo.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Notas.php')){
    require_once(app_path() . '/apis/Notas.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Expresso.php')){
    require_once(app_path() . '/apis/Expresso.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/P4.php')){
    require_once(app_path() . '/apis/P4.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Notificacoes.php')){
    require_once(app_path() . '/apis/Notificacoes.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Sia-Auth.php')){
    require_once(app_path() . '/apis/Sia-Auth.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Sia-Ui.php')){
    require_once(app_path() . '/apis/Sia-Ui.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Sargenteacao.php')){
    require_once(app_path() . '/apis/Sargenteacao.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Dados.php')){
    require_once(app_path() . '/apis/Dados.php'); // Inclui a Api se existir o arquivo
}

if(File::exists(app_path() . '/apis/Efetivo.php')){
    require_once(app_path() . '/apis/Efetivo.php'); // Inclui a Api se existir o arquivo
}
