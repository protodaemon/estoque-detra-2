<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradaConsumivel extends Model
{
    protected $table = 'entrada_consumivel';

    protected $primaryKey = 'entrada_consumivel_id';

    protected $fillable = [
        'produto_consumivel_id',
        'quantidade',
        'data_entrada',
        'observacao'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(ProdutoConsumivel::class, 'produto_consumivel_id', 'produtos_consumivel_id');
    }
}