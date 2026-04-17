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

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Reescreve o host do stream em tempo de leitura (ex.: dev atrás de proxy local).
     * Defina CAMERAS_LINK_URL_SEARCH e CAMERAS_LINK_URL_REPLACE no .env.
     */
    public function getLinkAttribute(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }
        $search = env('CAMERAS_LINK_URL_SEARCH');
        $replace = env('CAMERAS_LINK_URL_REPLACE');
        if ($search !== null && $search !== '' && $replace !== null) {
            return str_replace($search, (string) $replace, $value);
        }

        return $value;
    }
}
