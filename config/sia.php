<?php

/*
|--------------------------------------------------------------------------
| Integração SIA (autenticação)
|--------------------------------------------------------------------------
| Use config('sia.*') no código da aplicação — não use env() fora deste
| arquivo, senão valores somem após `php artisan config:cache`.
*/

$verifySsl = filter_var(env('SIA_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN);

$siaUrl = env('SIA_URL');
$dominio = env('DOMINIO_SIA');
$porta = (string) env('PORTA_SIA', '443');

$base = '';
if (is_string($siaUrl) && trim($siaUrl) !== '') {
    $base = rtrim($siaUrl, '/');
} elseif (is_string($dominio) && trim($dominio) !== '') {
    $d = rtrim($dominio, '/');
    if (preg_match('#^https?://#i', $d)) {
        $base = $d.(in_array($porta, ['443', '80', ''], true) ? '' : ':'.$porta);
    } else {
        $base = 'https://'.$d.(in_array($porta, ['443', '80', ''], true) ? '' : ':'.$porta);
    }
}

$pathAuth = ltrim((string) env('PATH_AUTH_SIA', 'api/auth'), '/');
$pathView = ltrim((string) env('PATH_VIEW_LOGIN_SIA', 'api/view'), '/');
$pathReset = ltrim((string) env('PATH_RESET_SENHA_SIA', 'api/password'), '/');

return [
    'verify_ssl' => $verifySsl,
    'http_base_url' => $base,
    'urls' => [
        'auth' => $base === '' ? '' : $base.'/'.$pathAuth,
        'view_login' => $base === '' ? '' : $base.'/'.$pathView,
        'reset_senha' => $base === '' ? '' : $base.'/'.$pathReset,
    ],
    'chave_assinatura' => env('SIA_CHAVE_ASSINATURA'),
    'id_software' => env('SIA_ID_SOFTWARE'),
];
