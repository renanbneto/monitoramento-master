<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateWebhookSignature
{
    public function handle(Request $request, Closure $next, string $sistema)
    {
        $secret = config("webhooks.secrets.{$sistema}");

        if (empty($secret)) {
            Log::warning("Webhook '{$sistema}': segredo não configurado.");
            abort(503, 'Integração não configurada.');
        }

        // Proteção anti-replay: rejeita timestamps fora da janela de tolerância
        $tolerance = (int) config('webhooks.timestamp_tolerance', 300);
        $tsHeader  = (int) $request->header('X-Timestamp', 0);
        if ($tolerance > 0 && abs(time() - $tsHeader) > $tolerance) {
            abort(401, 'Timestamp inválido ou expirado.');
        }

        $expected  = hash_hmac('sha256', $request->getContent(), $secret);
        $received  = $request->header('X-Signature-256', '');

        if (! hash_equals($expected, $received)) {
            Log::warning("Webhook '{$sistema}': assinatura HMAC inválida.", [
                'ip' => $request->ip(),
            ]);
            abort(403, 'Assinatura inválida.');
        }

        return $next($request);
    }
}
