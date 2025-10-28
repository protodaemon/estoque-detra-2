<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradaPatrimonio extends Model
{
    protected $table = 'entrada_patrimonio';

    protected $primaryKey = 'entrada_patrimonio_id';

    protected $fillable = [
        'produto_patrimonio_id',
        'quantidade',
        'data_entrada',
        'observacao'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(ProdutoPatrimonio::class, 'produto_patrimonio_id', 'produtos_patrimonio_id');
    }
}
