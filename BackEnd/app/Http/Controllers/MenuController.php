<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdutoDecoracao;
use App\Models\ProdutoArtesanato;
use App\Models\Locacao;


class MenuController extends Controller
{
    public function getEstatisticas(){
        return response()->json([
            'estatisticas' => [
                'decoracao' => ProdutoDecoracao::count(),
                'artesanato' => ProdutoArtesanato::count(),
                'locacao' => Locacao::whereIn('status', [0,1])->count(),

            ]
            ]);
    }
}
