<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
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
            'status',
            'status_checked_at',
            'status_response_ms',
    ];

    protected $casts = [
        'ativo'             => 'boolean',
        'status_checked_at' => 'datetime',
    ];

    public function getSenhaAttribute(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    public function setSenhaAttribute(?string $value): void
    {
        $this->attributes['senha'] = $value !== null && $value !== '' ? encrypt($value) : $value;
    }

    public function getUsuarioAttribute(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    public function setUsuarioAttribute(?string $value): void
    {
        $this->attributes['usuario'] = $value !== null && $value !== '' ? encrypt($value) : $value;
    }

    public function eventos()
    {
        return $this->belongsToMany(\App\Models\Evento::class, 'evento_camera');
    }

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
