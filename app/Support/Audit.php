<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Audit
{
    /**
     * Registra uma ação de auditoria.
     *
     * @param string      $acao       ex: 'camera.view', 'evento.create', 'lpr.consulta'
     * @param string|null $recurso    ex: 'Camera', 'Evento'
     * @param int|null    $recursoId
     * @param array       $detalhes   dados extras (nome do local, busca realizada, etc.)
     */
    public static function log(
        string $acao,
        ?string $recurso = null,
        ?int $recursoId  = null,
        array $detalhes  = []
    ): void {
        try {
            $request  = app(Request::class);
            $user     = Session::get('user');
            $authUser = Auth::user();

            AuditLog::create([
                'user_id'    => $authUser?->id,
                'user_rg'    => $user?->rg ?? null,
                'user_nome'  => $user?->nome ?? $authUser?->name ?? null,
                'acao'       => $acao,
                'recurso'    => $recurso,
                'recurso_id' => $recursoId,
                'ip'         => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'detalhes'   => $detalhes ?: null,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Nunca deixar o log de auditoria quebrar a requisição principal
            \Illuminate\Support\Facades\Log::error('Falha ao registrar auditoria: ' . $e->getMessage());
        }
    }
}
