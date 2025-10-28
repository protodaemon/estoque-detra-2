<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoPatrimonio extends Model
{
    protected $table = 'movimentacoes_patrimonio';
    protected $primaryKey = 'movimentacao_id';
    public $timestamps = false;

    protected $fillable = [
        'produto_patrimonio_id',
        'tipo_movimentacao',
        'localizacao_anterior_id',
        'localizacao_nova_id',
        'usuario_id',
        'observacao',
        'data_movimentacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
    ];

    

    public function produto()
    {
        return $this->belongsTo(ProdutoPatrimonio::class, 'produto_patrimonio_id', 'produtos_patrimonio_id')
            ->withTrashed(); // assim traz produtos mesmo que deletados
    }


    public function localizacaoAnterior()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_anterior_id', 'localizacao_id');
    }

    public function localizacaoNova()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_nova_id', 'localizacao_id');
    }

    public static function registrarCriacao($produtoId, $localizacaoId = null, $observacao = null)
    {
        return self::create([
            'produto_patrimonio_id' => $produtoId,
            'tipo_movimentacao' => 'criacao',
            'localizacao_nova_id' => $localizacaoId,
            'observacao' => $observacao,
            'data_movimentacao' => now()
        ]);
    }

    public static function registrarExclusao($produtoId, $localizacaoAtual = null, $observacao = null)
    {
        return self::create([
            'produto_patrimonio_id' => $produtoId,
            'tipo_movimentacao' => 'exclusao',
            'localizacao_anterior_id' => $localizacaoAtual,
            'observacao' => $observacao,
            'data_movimentacao' => now()
        ]);
    }

    public static function registrarMudancaLocalizacao($produtoId, $localizacaoAnterior, $localizacaoNova, $observacao = null)
    {
        return self::create([
            'produto_patrimonio_id' => $produtoId,
            'tipo_movimentacao' => 'mudanca_localizacao',
            'localizacao_anterior_id' => $localizacaoAnterior,
            'localizacao_nova_id' => $localizacaoNova,
            'observacao' => $observacao,
            'data_movimentacao' => now()
        ]);
    }
}
