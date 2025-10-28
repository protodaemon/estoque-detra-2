<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPedidoConsumivel extends Model
{
    /**
     * O nome da view associada com o model.
     */
    protected $table = 'view_pedido_consumivel';

    /**
     * Como a view é somente leitura, não há timestamps.
     */
    public $timestamps = false;
}