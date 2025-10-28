<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; // Importe a classe Hash para criptografia de senhas
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model
{
    use HasApiTokens, Notifiable;    // TOKEN DE PROTEÇÃO API NÃO CONSEGUI RESOLVER AINDA

    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'user',
        'senha',
        'nome',
        'email_rec',
    ];

    /**
     * A propriedade '$hidden' define quais colunas devem ser ocultadas quando
     * a model é convertida para um array ou JSON. É uma ótima prática de segurança
     * nunca expor a senha, mesmo que ela esteja criptografada.
     */
    protected $hidden = [
        'senha',
    ];

    /**
     * Este é um "Mutator". É um método especial que será executado automaticamente
     * sempre que tentarmos definir um valor para o atributo 'senha'.
     *
     * Ele garante que, antes de salvar a senha no banco de dados, ela seja
     * criptografada de forma segura usando o Hash do Laravel.
     *
     * @param  string  $value
     * @return void
     */
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }
}
