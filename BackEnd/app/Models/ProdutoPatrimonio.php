<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoPatrimonio extends Model
{
    use SoftDeletes;
    protected $table = 'produtos_patrimonio';
    protected $primaryKey = 'produtos_patrimonio_id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'foto',
        'descricao',
        'observacoes',
        'numero_identificacao',
        'categoria_patrimonio',
        'localizacao_id',
        'ativo'
    ];

    protected $dates = ['deleted_at']; // marca como campo de data

    public function categoria()
    {
        #relacionamento de pertence a 
        #cria um atalho inteligente, pois todo produto pertence a uma categoria 
        return $this->belongsTo(CategoriaPatrimonio::class, 'categoria_patrimonio', 'categoria_patrimonio_id');
    }
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_id', 'localizacao_id');
    }

    // Registra automaticamente movimentações via eventos do Eloquent.
    protected static function booted()
    {
        // Registrar criação
        static::created(function ($produto) {
            MovimentacaoPatrimonio::registrarCriacao(
                $produto->produtos_patrimonio_id,
                $produto->localizacao_id,
                'Produto cadastrado no sistema'
            );
        });

        // Registrar mudança de localização
        static::updating(function ($produto) {
            if ($produto->isDirty('localizacao_id')) {
                MovimentacaoPatrimonio::registrarMudancaLocalizacao(
                    $produto->produtos_patrimonio_id,
                    $produto->getOriginal('localizacao_id'),
                    $produto->localizacao_id,
                    'Localização alterada'
                );
            }
        });

        // Registrar exclusão
        /*static::deleting(function ($produto) {
            MovimentacaoPatrimonio::registrarExclusao(
                $produto->produtos_patrimonio_id,
                $produto->localizacao_id,
                'Produto removido do sistema'
            );
        });*/
    }
}
