<?php

namespace App\Http\Middleware;

use App\Support\Audit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Log
{
    // Rotas auditadas automaticamente: prefixo => ação
    private const ROTAS_AUDITADAS = [
        'prospeccoesLPR' => 'lpr.acesso',
        'cameras/view'   => 'camera.listagem',
        'eventos'        => 'evento.acesso',
        'mosaicos'       => 'mosaico.acesso',
        'auditoria'      => 'auditoria.acesso',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Só auditamos requisições autenticadas, não-AJAX, método GET (leituras)
        if (Auth::check() && !$request->ajax() && $request->isMethod('GET')) {
            $path = ltrim($request->path(), '/');
            foreach (self::ROTAS_AUDITADAS as $prefixo => $acao) {
                if (str_starts_with($path, $prefixo)) {
                    Audit::log($acao, null, null, ['url' => $path]);
                    break;
                }
            }
        }

        return $response;
    }
}
