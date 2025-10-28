<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaidaConsumivel extends Model
{
    protected $table = 'saida_consumivel';

    protected $primaryKey = 'saida_consumivel_id';

    protected $fillable = [
        'produto_consumivel_id',
        'quantidade',
        'data_saida',
        'observacao'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(ProdutoConsumivel::class, 'produto_consumivel_id', 'produtos_consumivel_id');
    }
}