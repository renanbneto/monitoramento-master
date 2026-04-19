<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Webservice Transporte Coletivo (URBS)
    |--------------------------------------------------------------------------
    | Documentação institucional: credencial na página do webservice; parâmetro
    | "c" em cada URL. Evite intervalos curtos de polling (ofício: ~2 min entre
    | posições; excesso pode ser tratado como DoS).
    */

    'base_url' => rtrim(env('URBS_TRANSPORTE_BASE_URL', 'https://transporteservico.urbs.curitiba.pr.gov.br'), '/'),

    'access_code' => env('URBS_TRANSPORTE_ACCESS_CODE', 'ea7b9'),

    'endpoints' => [
        'linhas' => env('URBS_TRANSPORTE_ENDPOINT_LINHAS', 'getLinhas.php'),
        'veiculos' => env('URBS_TRANSPORTE_ENDPOINT_VEICULOS', 'getVeiculos.php'),
    ],

    'cache' => [
        /* Resposta agregada /onibus (processamento veículos + enriquecimento) */
        'onibus_ttl' => (int) env('URBS_TRANSPORTE_CACHE_ONIBUS_TTL', 120),
        /* Lista de linhas (dados mais estáticos) */
        'linhas_ttl' => (int) env('URBS_TRANSPORTE_CACHE_LINHAS_TTL', 3600),
    ],

    'http' => [
        'timeout' => (float) env('URBS_TRANSPORTE_HTTP_TIMEOUT', 25),
        'connect_timeout' => (float) env('URBS_TRANSPORTE_HTTP_CONNECT_TIMEOUT', 10),
        // Em ambientes com proxy corporativo que faz inspeção SSL, definir como false
        'verify' => filter_var(env('URBS_TRANSPORTE_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'proxy' => [
        /*
        | Em APP_ENV=local o proxy é omitido por padrão (acesso direto).
        | Defina URBS_TRANSPORTE_USE_PROXY_IN_LOCAL=true se precisar do proxy também no dev.
        */
        'url' => env('URBS_TRANSPORTE_HTTP_PROXY', env('ONIBUS_HTTP_PROXY', 'http://proxy-02.pr.gov.br:8000')),
        'use_in_local' => filter_var(env('URBS_TRANSPORTE_USE_PROXY_IN_LOCAL', false), FILTER_VALIDATE_BOOLEAN),
    ],

];
