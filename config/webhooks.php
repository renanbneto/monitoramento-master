<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Segredos HMAC-SHA256 por sistema integrado
    |--------------------------------------------------------------------------
    | Cada sistema externo deve enviar o header X-Signature-256 com valor:
    | hash_hmac('sha256', $payloadBody, $secret)
    |
    | Gere segredos com: php artisan tinker --execute="echo bin2hex(random_bytes(32));"
    */
    'secrets' => [
        'viaturas'          => env('WEBHOOK_SECRET_VIATURAS'),
        'radios'            => env('WEBHOOK_SECRET_RADIOS'),
        'ocorrencias'       => env('WEBHOOK_SECRET_OCORRENCIAS'),
        'panico_mpf'        => env('WEBHOOK_SECRET_PANICO_MPF'),
        'panico_escola'     => env('WEBHOOK_SECRET_PANICO_ESCOLA'),
        'reconhecimento_fa' => env('WEBHOOK_SECRET_RECONHECIMENTO_FA'),
        'lpr'               => env('WEBHOOK_SECRET_LPR'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tolerância de timestamp (em segundos)
    |--------------------------------------------------------------------------
    | Rejeita requisições com X-Timestamp mais antigo/novo que este valor.
    | Protege contra replay attacks.
    */
    'timestamp_tolerance' => (int) env('WEBHOOK_TIMESTAMP_TOLERANCE', 300),
];
