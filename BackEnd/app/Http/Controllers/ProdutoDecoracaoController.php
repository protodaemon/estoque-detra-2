<?php

namespace App\Http\Controllers;

use App\Models\ProdutoDecoracao;
use Illuminate\Http\Request;
use App\Models\EntradaDecoracao; //chamando a model EntradaDecoração para popular ela na função store tbm...
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProdutoDecoracaoController extends Controller
{
    /*
    public function index(Request $request)
    {
        $query = ProdutoDecoracao::query();

        // Filtro por nome (qualquer parte do nome)
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Filtro por categoria (somente se selecionada)
        if ($request->filled('categoria')) {
            $query->where('categoria_decoracao', $request->categoria);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $perPage = $request->input('per_page', 12);

        return $query->orderBy('nome')->paginate($perPage);
    }
    public function mudarAtivo(Request $request, $id){
        $produto = ProdutoDecoracao::findOrFail($id);

        $produto->ativo = $request->input('ativo');
        $produto->save();

        return response()->json([
            'mensagem' => 'Produto ativado/desativado com sucesso',
            'produto' => $produto
        ]);
    }
    public function totalEstoque(){
        $total = ProdutoDecoracao::sum('qtd_disponivel');
        return response()->json([
            'total_estoque' => $total
        ]);
    }

    //função de store de produtos com compressão de foto 
    private function compressImage($file, $maxWidth = 800, $quality = 60)
    {
        // Instancia o gerenciador com driver GD (padrão na maioria dos servidores)
        $manager = new ImageManager(new Driver());

        // Lê a imagem do arquivo enviado
        $image = $manager->read($file->getRealPath());

        // Redimensiona proporcionalmente se passar da largura máxima
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Converte para JPEG e aplica compressão
        $image = $image->toJpeg($quality);

        // Nome do arquivo e caminho no storage
        $nomeArquivo = uniqid('prod_', true) . '.jpg';
        $caminhoRelativo = 'produtos_decoracao/' . $nomeArquivo;

        // Salva no disco público
        Storage::disk('public')->put($caminhoRelativo, (string) $image);

        return $caminhoRelativo; // Salva caminho relativo no banco
    }


    // Função store que usa compressImage para salvar foto otimizada
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:90|unique:produtos_decoracao,nome',
            'qtd_disponivel' => 'required|integer|min:0',
            'valor_locacao' => 'required|numeric|min:0',
            'categoria_decoracao' => 'nullable|exists:categoria_decoracao,categoria_decoracao_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $this->compressImage($request->file('foto'));
        }

        $produto = ProdutoDecoracao::create([
            'nome' => $request->nome,
            'qtd_disponivel' => $request->qtd_disponivel,
            'valor_locacao' => $request->valor_locacao,
            'categoria_decoracao' => $request->categoria_decoracao,
            'foto' => $caminhoFoto ? asset('storage/' . $caminhoFoto) : null,
        ]);

        EntradaDecoracao::create([
            'produto_decoracao_id' => $produto->produtos_decoracao_id,
            'quantidade' => $request->qtd_disponivel,
            'valor_unitario' => $request->valor_locacao,
            'data_entrada' => now()->toDateString(),
            'observacao' => 'Entrada automática ao criar o produto.'
        ]);

        return response()->json([
            'mensagem' => 'Produto criado com sucesso!',
            'produtos_decoracao_id' => $produto->produtos_decoracao_id
        ], 201);
    }
    public function update(Request $request, $id){
        $produto = ProdutoDecoracao::findOrFail($id);

        $request->validate([
            'nome' => 'sometimes|required|string|max:90|unique:produtos_decoracao,nome,' . $produto->produtos_decoracao_id . ',produtos_decoracao_id',
            'qtd_disponivel' => 'required|integer|min:0',
            'valor_locacao' => 'required|numeric|min:0',
            'categoria_decoracao' => 'nullable|exists:categoria_decoracao,categoria_decoracao_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        if($request->has('nome')){
            $produto->nome = $request->nome;
        }
        if($request->has('qtd_disponivel')){
            $produto->qtd_disponivel = $request->qtd_disponivel;
        }
        if($request->has('valor_locacao')){
            $produto->valor_locacao = $request->valor_locacao;
        }
        if($request->has('categoria_decoracao')){
            $produto->categoria_decoracao = $request->categoria_decoracao;
        }
        if($request->has('foto')){
            $fotoCaminho = $this->compressImage($request->file('foto'));
            $produto->foto = $fotoCaminho ? asset('storage/'.$fotoCaminho) : null;
        }
        if($request->has('remover_foto') && $request->remover_foto){
            if($produto->foto){
                $caminho=str_replace(asset('storage/'), '', $produto->foto);
                Storage::disk('public')->delete($caminho);
            }
            $produto->foto = null;
        }

        $produto->save();

        return response()->json([
            'mensagem' => 'Produto atualizado com sucesso',
            'produtos_decoracao_id' => $produto->produtos_decoracao_id
        ], 200);
    }
    public function delete($id){
        $produto = ProdutoDecoracao::findOrFail($id);
        if ($produto->locacoes()->exists()) {
            return response()->json([
            'erro' => 'Não é possível deletar este produto. Ele possui locações ou histórico de locações.'
        ], 422);
        }
        EntradaDecoracao::where('produto_decoracao_id', $produto->produtos_decoracao_id)->delete();

        $produto->delete();

        return response()->json([
            'mensagem' => 'Produto excluído com sucesso.'
        ], 200);
    }
        */
}
