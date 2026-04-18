<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    private $dados = null;

    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    public function adminlte_image()
    {
        return route('fotos_Efetivo',Session::get('user')->cpf ? Session::get('user')->cpf : '0');
    }

    //Retorna email do usuário para perfil, se existir
    public function adminlte_desc()
    {
        if (isset(Session::get('user')->email)) {
            return Session::get('user')->email;
        }
    }

    //Define Rota padrão para perfil
    public function adminlte_profile_url()
    {
        return 'perfil';
    }

    //Define rota padrão para logout perfil
    public function adminlte_logout_url()
    {
        return 'logout';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mosaico',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cameraMosaics()
    {
        return $this->hasMany(\App\Models\CameraMosaic::class);
    }
}
