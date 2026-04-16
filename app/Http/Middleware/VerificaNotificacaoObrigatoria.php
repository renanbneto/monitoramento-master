<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificaNotificacaoObrigatoria
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(session()->get('user') && session()->get('user')->notificacao_obrigatoria > 0){
            if (!$request->routeIs('auth','exibirNotificacoes','logout','atualizaSessionNotificacoesObrigatorias','login','confirmarLeitura_Notificacoes','listarNotificacoes_Notificacoes','sessao', 'listarNotificacoesHistorico_Notificacoes')) {
                return redirect()->route('exibirNotificacoes');
            }
        }

        return $next($request);
    }
}
