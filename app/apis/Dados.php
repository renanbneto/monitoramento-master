<?php

namespace App\apis;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Spatie\QueryBuilder\QueryBuilder;

$api = new Api("Dados"); // Instancia a API 

// DEFINIÇÃO DAS ROTAS LOCAIS DESTE SERVIÇO
Route::prefix($api->apiName)->group(function () use($api) {

    Route::group(['middleware' => ['auth','auth2']],function() use($api){

        Route::get('inativos', function() use($api){
            return $api->get('/api/inativos', request()->all());
        })->name('inativos.inativos');

        Route::get('teste', function() use($api){
            return $api->get('/api/teste',request()->all());
        })->name('teste_'.$api->apiName);
       

        Route::get('emplacamentos/placa/{placa}',function($placa) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/emplacamentos/placa/{$placa}",$dados);

        })->name('emplacamentosPlaca');    
    
        Route::get('emplacamentos/chassi/{chassi}',function($chassi) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/emplacamentos/chassi/{$chassi}",$dados);

        })->name('emplacamentosChassi');

        Route::get('emplacamentos/renavam/{renavam}',function($renavam) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/emplacamentos/renavam/{$renavam}",$dados);

        })->name('emplacamentosRenavam');

        Route::get('emplacamentos/proprietario/{cpfCnpj}',function($cpfCnpj) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/emplacamentos/proprietario/{$cpfCnpj}",$dados);

        })->name('emplacamentosProprietario');

        Route::get('embarcacoes/inscricao/{inscricao}',function($inscricao) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/embarcacoes/inscricao/{$inscricao}",$dados);

        })->name('embarcacoesInscricao');

        Route::get('embarcacoes/nome/{nome}',function($nome) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/embarcacoes/nome/{$nome}",$dados);

        })->name('embarcacoesNome');
        
        Route::get('embarcacoes/proprietario/{cpfCnpj}',function($cpfCnpj) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/embarcacoes/proprietario/{$cpfCnpj}",$dados);

        })->name('embarcacoesProprietario');

        Route::get('estados',function() use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/estados",$dados);

        })->name('estados');

        Route::get('estados/{estado_id}/municipios',function($estado_id) use($api){

            try {
                
                $crypt = new Encrypter(env('SIA_CRYPT_KEY'),env('SIA_CRYPT_CHYPHER'));
                $user = $crypt->encrypt([
                    'cpf' => Session::get('user')->cpf,
                    'rg' => Session::get('user')->rg,
                ]);

            } catch (\Throwable $th) {
                return response([$th->getMessage],500);
            }

            $dados = array_merge(request()->all(),[
                'user' => $user,
            ]);

            return $api->get("/api/cortex/estados/{$estado_id}/municipios",$dados);

        })->name('municipios');


    });

});

?>
