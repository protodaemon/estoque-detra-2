<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaConsumivel extends Model
{
    protected $table = 'categoria_consumivel';

    protected $primaryKey = 'categoria_consumivel_id';

    public $timestamps = false;

    protected $fillable = ['nome', 'descricao'];
}
