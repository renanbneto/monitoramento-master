<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Somente APP_ENV=local + LOCAL_AUTH_BYPASS=true.
 * Evita login/captcha e preenche Auth + sessão "user" como após o SIA (sem APIs externas).
 */
class LocalDevBypassAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! app()->environment('local')) {
            return $next($request);
        }

        if (! filter_var(env('LOCAL_AUTH_BYPASS', false), FILTER_VALIDATE_BOOLEAN)) {
            return $next($request);
        }

        if (Auth::check() && Session::has('user')) {
            return $next($request);
        }

        $email = env('LOCAL_AUTH_EMAIL', 'dev@local.test');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('LOCAL_AUTH_NAME', 'Desenvolvimento Local'),
                'password' => bcrypt(Str::random(32)),
            ]
        );

        $payload = (object) [
            'email' => $email,
            'nome' => env('LOCAL_AUTH_NAME', 'Desenvolvimento Local'),
            'senha' => $user->password,
            'usuario' => env('LOCAL_AUTH_USUARIO', 'dev.local'),
            'cpf' => env('LOCAL_AUTH_CPF', '00000000000'),
            'rg' => env('LOCAL_AUTH_RG', '0'),
            'id' => $user->id,
            'policial_id' => 0,
            'policial_id_meta4' => 0,
            'opm_m4_id' => 0,
            'opm_id' => 0,
            'local_id' => 0,
            'cdopm_meta4_topo' => (int) env('LOCAL_AUTH_CDO_META4', 1),
            'opm_id_meta4_descricao' => 'OPM Dev',
            'autorizacoes' => env('LOCAL_AUTH_AUTORIZACOES', 'Administrador;Auditoria'),
            'cdopm_pmpr' => null,
            'opm_pmpr_id' => null,
            'auth_expresso' => null,
            'opms_subordinadas' => [],
            'notificacao_obrigatoria' => 0,
        ];

        $user->setDados($payload);
        Auth::login($user, true);
        Session::put('user', $payload);
        Session::put('autorizacoes', $payload->autorizacoes);

        return $next($request);
    }
}
