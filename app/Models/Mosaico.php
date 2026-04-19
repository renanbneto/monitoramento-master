<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mosaico extends Model
{
    protected $fillable = ['user_id', 'nome', 'descricao'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cameras()
    {
        return $this->belongsToMany(Camera::class, 'mosaico_camera')
                    ->withPivot('ordem')
                    ->orderBy('mosaico_camera.ordem');
    }
}
