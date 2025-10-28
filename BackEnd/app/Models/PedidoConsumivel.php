<?php

namespace App\Models;

/* namespace App\Models;
<!-- <!-?php
use Illuminate\Database\Eloquent\Model;

class PedidoConsumivel extends Model
{
    protected $table = 'pedido_consumivel';
    protected $primaryKey = 'pedido_consumivel_id';
    public $timestamps = false;

    protected $fillable = [
        'pedido_consumivel_id',
        'status',
        'data_pedido',
        'data_cancelamento',
        'data_alteração',
        'observacoes'
    ];
    
    public function itens()
    {
        // Um pedido tem muitos 'ProdutoPedidoConsumivel'
        return $this->hasMany(ProdutoPedidoConsumivel::class, 'pedido_consumivel_id');
    } 
}*/



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
        'data_alteracao', // Corrigido: era 'data_alteração'
        'observacoes'
    ];
    
    protected $casts = [
        'data_pedido' => 'datetime',
        'data_cancelamento' => 'datetime',
        'data_alteracao' => 'datetime'
    ];
    
    public function itens()
    {
        return $this->hasMany(ProdutoPedidoConsumivel::class, 'pedido_consumivel_id');
    }
}
