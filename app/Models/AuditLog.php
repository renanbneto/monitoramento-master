<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_rg', 'user_nome',
        'acao', 'recurso', 'recurso_id',
        'ip', 'user_agent', 'detalhes', 'created_at',
    ];

    protected $casts = [
        'detalhes'   => 'array',
        'created_at' => 'datetime',
    ];
}
