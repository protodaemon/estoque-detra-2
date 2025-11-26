<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoConsumivel extends Model
{
    protected $table = 'pedido_consumivel';
    protected $primaryKey = 'pedido_consumivel_id';
    public $timestamps = false;
    
    // ✅ IMPORTANTE: Para Auto-Increment funcionar
    public $incrementing = true;
    
    // ✅ Tipo da chave primária
    protected $keyType = 'int';

    protected $fillable = [
        'status',
        'data_pedido',
        'data_cancelamento',
        'data_confirmacao',
        'data_alteracao',
        'observacoes',
        'usuario_id',
        'observacao_cancelamento'
    ];
    
    protected $casts = [
        'data_pedido' => 'datetime',
        'data_cancelamento' => 'datetime',
        'data_confirmacao' => 'datetime',
        'data_alteracao' => 'datetime'
    ];
    
    public function itens()
    {
        return $this->hasMany(ProdutoPedidoConsumivel::class, 'pedido_consumivel_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
