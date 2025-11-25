<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'user',
        'senha',
        'nome',
        'email_rec',
        'nivel_acesso' //dois niveis: padrao e admin
    ];

    protected $hidden = [
        'senha',
    ];

    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = bcrypt($value);
    }

    // Métodos exigidos pelo JWT

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Laravel espera um campo "password", então vamos mapear:
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
