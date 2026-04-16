<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $connection = 'auditoria';
    protected $table = 'logs';

    protected $fillable = [
        'sistema',
        'nome',
        'rg',
        'cpf',
        'url',
        'ip',
        'login',
        'acao',
        'created_time',
        'dados',
        'detalhes',
        'created_at',
        'updated_at'
    ];
}
