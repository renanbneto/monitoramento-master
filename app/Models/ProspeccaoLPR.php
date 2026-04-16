<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProspeccaoLPR extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'cidade',
        'bairro',
        'endereco',
        'sentido',
        'cadastrada_por',
        'cadastrada_por_cpf',
        'user_id',
        'lat',
        'lng',
    ] ;
}
