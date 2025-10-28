<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPatrimonio extends Model
{
    protected $table = 'categoria_patrimonio';

    protected $primaryKey = 'categoria_patrimonio_id';

    public $timestamps = false;

    protected $fillable = ['nome', 'descricao'];
}
