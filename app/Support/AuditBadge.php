<?php

namespace App\Support;

class AuditBadge
{
    public static function cor(string $acao): string
    {
        if (str_contains($acao, 'delete') || str_contains($acao, 'destroy')) return 'danger';
        if (str_contains($acao, 'create') || str_contains($acao, 'store'))   return 'success';
        if (str_contains($acao, 'update') || str_contains($acao, 'edit'))    return 'warning';
        if (str_contains($acao, 'lpr'))                                       return 'purple';
        if (str_contains($acao, 'auditoria'))                                 return 'dark';
        return 'info';
    }
}
