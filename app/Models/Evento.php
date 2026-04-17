<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'local_nome',
        'lat',
        'lng',
        'data_inicio',
        'data_fim',
        'ativo',
    ];

    protected $casts = [
        'ativo'       => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',
    ];

    public function cameras()
    {
        return $this->belongsToMany(Camera::class, 'evento_camera');
    }
}
