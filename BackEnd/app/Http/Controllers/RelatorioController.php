<?php

namespace App\Http\Controllers;

use App\Models\ProdutoPatrimonio;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function relatorioPatrimonio()
    {
        // Busca dados no banco
        //$produtos = ProdutoPatrimonio::all();
        $produtos = ProdutoPatrimonio::where('ativo', true)->get();

        // Gera a view em HTML
        $pdf = Pdf::loadView('relatorios.patrimonio', compact('produtos'));

        // Faz download direto
        return $pdf->download('relatorio_patrimonio.pdf');
    }
}
