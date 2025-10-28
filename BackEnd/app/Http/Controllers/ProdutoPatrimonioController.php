<?php

namespace App\Http\Controllers;

use App\Models\ProdutoPatrimonio;
use App\Models\MovimentacaoPatrimonio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProdutoPatrimonioController extends Controller
{
    public function index(Request $request)
    {
        $query = ProdutoPatrimonio::query();

        // Filtro por nome (qualquer parte do nome)
        if ($request->filled('nome')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->nome . '%')
                    ->orWhere('numero_identificacao', 'like', '%' . $request->nome . '%');
            });
        }


        // Filtro por categoria (somente se selecionada)
        if ($request->filled('categoria')) {
            $query->where('categoria_patrimonio', $request->categoria);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $perPage = $request->input('per_page', 12);

        return $query->orderBy('nome')->paginate($perPage);
    }

    public function getAtivos(Request $request){
        $produtos = ProdutoPatrimonio::where('ativo', true)->get();

        return response()->json($produtos);
    }

    public function mudarAtivo(Request $request, $id)
    {
        printf("teste");
        $produto = ProdutoPatrimonio::findOrFail($id);

        printf("status produto: ", $produto->ativo);
        $produto->ativo = $request->input('ativo');
        printf("novo status: ", $produto->ativo);
        //$produto->ativo = !$produto->ativo;
        $produto->save();



        return response()->json([
            'mensagem' => 'Produto ativado/desativado com sucesso',
            'produto' => $produto
        ]);
    }

    public function totalEstoque(Request $request)
    {
        $categoriaId = $request->input('categoria_id');

        $totalGeral = ProdutoPatrimonio::count();

        $totalCategoria = null;
        if ($categoriaId) {
            $totalCategoria = ProdutoPatrimonio::where('categoria_patrimonio', $categoriaId)->count();
        }

        return response()->json([
            'total_estoque' => $totalGeral,
            'total_categoria' => $totalCategoria,
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
        $caminhoRelativo = 'produtos_patrimonio/' . $nomeArquivo;

        // Salva no disco público
        Storage::disk('public')->put($caminhoRelativo, (string) $image);

        return $caminhoRelativo; // Salva caminho relativo no banco
    }


    // Função store que usa compressImage para salvar foto otimizada
    public function store(Request $request)
    {
        $request->validate([
            //'nome' => 'nullable|string|max:90|unique:produtos_patrimonio,nome',
            'descricao' => 'required|string|max:500',
            'observacoes' => 'nullable|string|max:500',
            'localizacao_id' => 'required|exists:localizacoes,localizacao_id',
            'numero_identificacao' => 'nullable|digits:5|unique:produtos_patrimonio,numero_identificacao',
            'categoria_patrimonio' => 'nullable|exists:categoria_patrimonio,categoria_patrimonio_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $this->compressImage($request->file('foto'));
        }

        $produto = ProdutoPatrimonio::create([
            //'nome' => $request->nome,
            'descricao' => $request->descricao,
            'observacoes' => $request->observacoes,
            'numero_identificacao' => $request->numero_identificacao,
            'categoria_patrimonio' => $request->categoria_patrimonio,
            'localizacao_id' => $request->localizacao_id,
            'foto' => $caminhoFoto ? asset('storage/' . $caminhoFoto) : null,
        ]);

        /*EntradaDecoracao::create([
            'produto_decoracao_id' => $produto->produtos_decoracao_id,
            'quantidade' => $request->qtd_disponivel,
            'valor_unitario' => $request->valor_locacao,
            'data_entrada' => now()->toDateString(),
            'observacao' => 'Entrada automática ao criar o produto.'
        ]);*/


        return response()->json([
            'mensagem' => 'Produto criado com sucesso!',
            'produtos_patrimonio_id' => $produto->produtos_patrimonio_id
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $produto = ProdutoPatrimonio::findOrFail($id);

        $request->validate([
            //'nome' => 'sometimes|required|string|max:90|unique:produtos_patrimonio,nome,' . $produto->produtos_patrimonio_id . ',produtos_patrimonio_id',
            'descricao' => 'sometimes|nullable|string|max:500',
            'observacoes' => 'sometimes|nullable|string|max:500',
            'localizacao_id' => 'required|exists:localizacoes,localizacao_id',
            //'numero_identificacao' => 'sometimes|required|digits:5|unique:produtos_patrimonio,numero_identificacao,' . $produto->produtos_patrimonio_id . ',produtos_patrimonio_id',
            'categoria_patrimonio' => 'nullable|exists:categoria_patrimonio,categoria_patrimonio_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        if ($request->has('nome')) {
            $produto->nome = $request->nome;
        }
        if ($request->has('categoria_patrimonio')) {
            $produto->categoria_patrimonio = $request->categoria_patrimonio;
        }
        if ($request->has('foto')) {
            $fotoCaminho = $this->compressImage($request->file('foto'));
            $produto->foto = $fotoCaminho ? asset('storage/' . $fotoCaminho) : null;
        }
        if ($request->has('remover_foto') && $request->remover_foto) {
            if ($produto->foto) {
                $caminho = str_replace(asset('storage/'), '', $produto->foto);
                Storage::disk('public')->delete($caminho);
            }
            $produto->foto = null;
        }
        if ($request->has('descricao')) {
            $produto->descricao = $request->descricao;
        }
        if ($request->has('observacoes')) {
            $produto->observacoes = $request->observacoes;
        }
        /*if ($request->has('numero_identificacao')) {
            $produto->numero_identificacao = $request->numero_identificacao;
        }*/
        if ($request->has('localizacao_id')) {
            $produto->localizacao_id = $request->localizacao_id;
        }

        $produto->save();

        return response()->json([
            'mensagem' => 'Produto atualizado com sucesso',
            'produtos_patrimonio_id' => $produto->produtos_patrimonio_id
        ], 200);
    }

    /*public function delete($id)
    {
        $produto = ProdutoPatrimonio::findOrFail($id);
      
        $produto->delete();

        return response()->json([
            'mensagem' => 'Produto excluído com sucesso.'
        ], 200);
    }*/

    public function delete(Request $request, $id)
    {
        // Validar que o motivo foi fornecido
        $request->validate([
            'motivo_exclusao' => 'required|string|min:5|max:500'
        ], [
            'motivo_exclusao.required' => 'É obrigatório informar o motivo da exclusão',
            'motivo_exclusao.min' => 'O motivo deve ter no mínimo 5 caracteres',
            'motivo_exclusao.max' => 'O motivo deve ter no máximo 500 caracteres'
        ]);

        $produto = ProdutoPatrimonio::findOrFail($id);

        // Registrar a movimentação ANTES de deletar
        MovimentacaoPatrimonio::registrarExclusao(
            $produto->produtos_patrimonio_id,
            $produto->localizacao_id,
            $request->motivo_exclusao
        );

        // Deletar o produto
        $produto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produto excluído com sucesso'
        ]);
    }

}
