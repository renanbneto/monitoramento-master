<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    // Domínios externos usados nas views (Leaflet, AdminLTE, etc.)
    private const SCRIPT_ORIGINS = "'self' 'unsafe-inline' 'unsafe-eval' unpkg.com cdnjs.cloudflare.com cdn.datatables.net cdn.jsdelivr.net code.jquery.com maxcdn.bootstrapcdn.com";
    private const STYLE_ORIGINS  = "'self' 'unsafe-inline' unpkg.com fonts.googleapis.com use.fontawesome.com cdnjs.cloudflare.com cdn.datatables.net cdn.jsdelivr.net maxcdn.bootstrapcdn.com";
    private const FONT_ORIGINS   = "'self' data: fonts.gstatic.com fonts.googleapis.com use.fontawesome.com maxcdn.bootstrapcdn.com cdnjs.cloudflare.com";
    private const CONNECT_ORIGINS = "'self' api.openweathermap.org nominatim.openstreetmap.org tile.openweathermap.org nominatim.openstreetmap.org";
    // Imagens: câmeras podem ser de qualquer IP; tiles de mapa também
    private const IMG_ORIGINS    = "* data: blob:";
    // Frames: sistemas institucionais integrados ao mapa
    private const FRAME_ORIGINS  = "'self' docs.google.com drive.google.com helios.sesp.pr.gov.br web.helios.sesp.pr.gov.br sigap.pm.pr.gov.br bi.celepar.parana bi.redeexecutiva.pr.gov.br cape.sesp.parana";

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src "  . self::SCRIPT_ORIGINS,
            "style-src "   . self::STYLE_ORIGINS,
            "font-src "    . self::FONT_ORIGINS,
            "img-src "     . self::IMG_ORIGINS,
            "media-src "   . self::IMG_ORIGINS,
            "connect-src " . self::CONNECT_ORIGINS,
            "frame-src "   . self::FRAME_ORIGINS,
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS apenas em produção (HTTPS)
        if ($request->isSecure() && ! app()->isLocal()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Remove header que revela tecnologia
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
