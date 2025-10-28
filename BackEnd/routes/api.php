<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoriaPatrimonioController;
use App\Http\Controllers\CategoriaConsumivelController;
use App\Http\Controllers\ProdutoPatrimonioController;
use App\Http\Controllers\EntradaPatrimonioController;
use App\Http\Controllers\EntradaConsumivelController;
use App\Http\Controllers\SaidaConsumivelController;
use App\Http\Controllers\ProdutoConsumivelController;
use App\Http\Controllers\PedidoConsumivelController;
use App\Http\Controllers\ProdutoPedidoConsumivelController;
use App\Http\Controllers\MovimentacaoPatrimonioController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\MenuController;
use App\Models\ProdutoDecoracao;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\LocalizacaoController;

Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/registro', [UsuarioController::class, 'register']);
Route::middleware('auth.token')->post('/logout', [UsuarioController::class, 'logout']);

//Categorias Patrimonio
Route::get('/categorias-patrimonio', [CategoriaPatrimonioController::class, 'index']);
Route::post('/categorias-patrimonio', [CategoriaPatrimonioController::class, 'store']);

//Categorias Consumivel
Route::get('/categorias-consumivel', [CategoriaConsumivelController::class, 'index']);
Route::post('/categorias-consumivel', [CategoriaConsumivelController::class, 'store']);

//Produto Patrimonio
Route::get('/produtos-patrimonio', [ProdutoPatrimonioController::class, 'index']);
Route::get('/patrimonio-ativos', [ProdutoPatrimonioController::class, 'getAtivos']);
Route::post('/produtos-patrimonio', [ProdutoPatrimonioController::class, 'store']);
Route::get('/total-estoque', [ProdutoPatrimonioController::class, 'totalEstoque']);
Route::put('/editar-patrimonio/{id}', [ProdutoPatrimonioController::class, 'update']);
Route::put('/mudar-ativo-patrimonio/{id}', [ProdutoPatrimonioController::class, 'mudarAtivo']);
Route::delete('/deletar-patrimonio/{id}', [ProdutoPatrimonioController::class, 'delete']);

//Movimentações
Route::get('/movimentacoes-patrimonio', [MovimentacaoPatrimonioController::class, 'index']);

//Localizações
Route::get('/localizacoes', [LocalizacaoController::class, 'index']);

//Produto Consumível
Route::get('/produtos-consumivel', [ProdutoConsumivelController::class, 'index']);
Route::get('/consumivel-ativos', [ProdutoConsumivelController::class, 'getAtivos']);
Route::post('/produtos-consumivel', [ProdutoConsumivelController::class, 'store']);
//Route::get('/total-estoque', [ProdutoDecoracaoController::class, 'totalEstoque']);
Route::put('/editar-consumivel/{id}', [ProdutoConsumivelController::class, 'update']);
Route::put('/mudar-ativo-consumivel/{id}', [ProdutoConsumivelController::class, 'mudarAtivo']);
Route::delete('/deletar-consumivel/{id}', [ProdutoConsumivelController::class, 'delete']);

Route::post('/criar-pedido-com-itens-consumivel', [PedidoConsumivelController::class, 'criarPedidoCompleto']);
Route::get('/pedido-consumivel', [PedidoConsumivelController::class, 'index']);
Route::post('/criar-pedido-consumivel', [PedidoConsumivelController::class, 'store']);
Route::put('/editar-pedido-consumivel/{id}', [PedidoConsumivelController::class, 'update']);
Route::delete('/deletar-pedido-consumivel/{id}', [PedidoConsumivelController::class, 'delete']);

Route::post('/criar_produto_pedido_consumivel', [ProdutoPedidoConsumivelController::class, 'store']);

//Relatórios
Route::get('/relatorio/patrimonio', [RelatorioController::class, 'relatorioPatrimonio']);

//Entrada Patrimônio
Route::get('/entrada-patrimonio', [EntradaPatrimonioController::class, 'index']);
Route::post('/entrada-patrimonio', [EntradaPatrimonioController::class, 'store']);
//Entrada Consumível
Route::get('/entrada-consumivel', [EntradaConsumivelController::class, 'index']);
Route::post('/entrada-consumivel', [EntradaConsumivelController::class, 'store']);
//Saída Consumível
Route::get('/saida-consumivel', [SaidaConsumivelController::class, 'index']);
Route::post('/saida-consumivel', [SaidaConsumivelController::class, 'store']);

// Criação do pedido consumível


// Route::post('/criar-pedido', )

//outros
//Route::get('/estatisticas', [MenuController::class, 'getEstatisticas']);
//Route::get('/categorias', [LocacaoController::class, 'listarCategorias']);

//stub
// Route::post('/criar-lista', [LocacaoController::class, 'store']);

Route::middleware('auth.token')->group(function () {


  /*  Route::get('/usuario-logado', function (Request $request) {
        return response()->json([
            'message' => 'Token válido!',
            'usuario' => $request->auth_user
        ]);
    });

    Route::get('/rota-secreta', function () {
        return response()->json(['message' => 'Você acessou uma rota protegida!']);
    }); */


});