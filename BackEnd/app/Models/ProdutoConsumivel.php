<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoConsumivel extends Model
{
    protected $table = 'produtos_consumivel';
    protected $primaryKey = 'produtos_consumivel_id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'foto',
        'descricao',
        'observacoes',
        'quantidade',
        'categoria_consumivel',
        'ativo'
    ];

    public function categoria()
    {
        #relacionamento de pertence a 
        #cria um atalho inteligente, pois todo produto pertence a uma categoria 
        return $this->belongsTo(CategoriaConsumivel::class, 'categoria_consumivel', 'categoria_consumivel_id');
    }
    /*public function locacoes()
    {
        return $this->hasMany(LocacaoItem::class, 'produto_decoracao_id');
    }*/
}
