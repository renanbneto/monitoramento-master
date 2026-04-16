<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
            'servidor',
            'cidade',
            'ip',
            'porta',
            'camera',
            'local_nome',
            'lat',
            'lng',
            'usuario',
            'senha',
            'protocolo',
            'vms',
            'formato',
            'hostname',
            'link',
            'ativo',
    ];
}
