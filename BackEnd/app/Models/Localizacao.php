<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    protected $table = 'localizacoes';
    protected $primaryKey = 'localizacao_id';

    protected $fillable = ['nome'];

    // Uma localização tem vários produtos
    public function produtos()
    {
        return $this->hasMany(ProdutoPatrimonio::class, 'localizacao_id', 'localizacao_id');
    }
}