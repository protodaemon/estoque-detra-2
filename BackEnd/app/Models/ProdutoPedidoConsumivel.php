<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPedidoConsumivel extends Model
{
    // O nome da tabela associada com o model.
    // Necessário porque o nome da sua tabela não segue a convenção plural do Laravel.
    
    protected $table = 'produtos_pedido_consumivel';

    // A chave primária da tabela.
    // Necessário porque sua chave não se chama 'id'.

    protected $primaryKey = 'produtos_pedido_consumivel_id';

    // Indica ao Eloquent para não gerenciar as colunas 'created_at' e 'updated_at'.
    // Necessário porque sua tabela não possui essas colunas.

    public $timestamps = false;

    // Os atributos que podem ser atribuídos em massa.
    // Essencial para poder usar o método Model::create().
    
    protected $fillable = [
        'pedido_consumivel_id',
        'produtos_consumivel_id',
        'quantidade'
    ];

    // Define o relacionamento: este item do pedido pertence a um PedidoConsumivel.
    public function pedido()
    {
        return $this->belongsTo(PedidoConsumivel::class, 'pedido_consumivel_id');
    }

    // Define o relacionamento: este item do pedido refere-se a um ProdutoConsumivel.
    public function produto()
    {
        return $this->belongsTo(ProdutoConsumivel::class, 'produtos_consumivel_id');
    }
}
