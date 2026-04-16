<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class Autorizacao
{

    public static function can($necessarias)
    {
        $possuidas = Str::of(Session::get('autorizacoes'))->explode(';');

        if(!self::autorizar($necessarias,$possuidas)){
            return false;
        }
        return true;
    }

    private static function autorizar($necessarias,$possuidas)
    {
        foreach ($necessarias as $necessaria) {
            foreach ($possuidas as $possuida){
                if($possuida === $necessaria)
                    return true;
            }
        }
        return false;
    }

    public static function autorizacaoLocal(){
        
        try {
            
            $query = \App\Models\Autorizacao::query();
            $user = Session::get('user');
            
            if($user->rg){
                $query->where('rg',$user->rg);
            }
            
            if($user->cpf){
                $query->where('cpf',$user->cpf);
            }
            
            $query->where('situacao',true);
            
            $autorizacao = $query->firstOrFail();

            $autorizacoes = Session::get('autorizacoes');

            
            $autorizacoes .= ";".$autorizacao->acesso;


            
            Session::put('autorizacoes',$autorizacoes);

        } catch (\Throwable $th) {

            return null;   
        }

    }
}