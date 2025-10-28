<?php

namespace App\Http\Controllers;

use App\Models\ProdutoConsumivel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class ProdutoConsumivelController extends Controller
{
    public function index(Request $request)
    {
        $query = ProdutoConsumivel::query();

        // Filtro por nome (qualquer parte do nome)
        if ($request->filled('nome')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->nome . '%');
            });
        }


        // Filtro por categoria (somente se selecionada)
        if ($request->filled('categoria')) {
            $query->where('categoria_consumivel', $request->categoria);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $perPage = $request->input('per_page', 12);

        return $query->orderBy('nome')->paginate($perPage);
    }

    public function getAtivos(Request $request){
        $produtos = ProdutoConsumivel::where('ativo', true)->get();

        return response()->json($produtos);
    }

    public function mudarAtivo(Request $request, $id)
    {
        printf("teste");
        $produto = ProdutoConsumivel::findOrFail($id);

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
    /*public function totalEstoque()
    {
        $total = Produtoconsumivel::sum('qtd_disponivel');
        return response()->json([
            'total_estoque' => $total
        ]);
    }*/

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
        $caminhoRelativo = 'produtos_consumivel/' . $nomeArquivo;

        // Salva no disco público
        Storage::disk('public')->put($caminhoRelativo, (string) $image);

        return $caminhoRelativo; // Salva caminho relativo no banco
    }


    // Função store que usa compressImage para salvar foto otimizada
    public function store(Request $request)
    {
        // Debug temporário
        Log::info('Dados recebidos:', $request->all());
        try {
            $request->validate([
                'nome' => 'required|string|max:90|unique:produtos_consumivel,nome',
                'descricao' => 'nullable|string|max:500',
                'observacoes' => 'nullable|string|max:500',
                'quantidade' => 'nullable',
                'categoria_consumivel' => 'required|exists:categoria_consumivel,categoria_consumivel_id',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação:', $e->errors());
            return response()->json([
                'mensagem' => 'Erro de validação',
                'erros' => $e->errors()
            ], 422);
        }



        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $this->compressImage($request->file('foto'));
        }

        $quantidade = $request->quantidade;
        // atribui 0 a quantidade se null
        $quantidade = is_null($quantidade) ? 0 : $quantidade;

        $produto = ProdutoConsumivel::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'observacoes' => $request->observacoes,
            'quantidade' => $quantidade,
            'categoria_consumivel' => $request->categoria_consumivel,
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
            'produtos_consumivel_id' => $produto->produtos_consumivel_id,
            'response' => $request->all()
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $produto = ProdutoConsumivel::findOrFail($id);

        $request->validate([
            'nome' => 'sometimes|required|string|max:90|unique:produtos_consumivel,nome,' . $produto->produtos_consumivel_id . ',produtos_consumivel_id',
            'descricao' => 'sometimes|nullable|string|max:500',
            'observacoes' => 'sometimes|nullable|string|max:500',
            'quantidade' => 'sometimes|nullable',
            'categoria_consumivel' => 'nullable|exists:categoria_consumivel,categoria_consumivel_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        if ($request->has('nome')) {
            $produto->nome = $request->nome;
        }
        if ($request->has('categoria_consumivel')) {
            $produto->categoria_consumivel = $request->categoria_consumivel;
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

        $produto->save();

        return response()->json([
            'mensagem' => 'Produto atualizado com sucesso',
            'produtos_consumivel_id' => $produto->produtos_consumivel_id
        ], 200);
    }
    public function delete($id)
    {
        $produto = ProdutoConsumivel::findOrFail($id);
        /*if ($produto->locacoes()->exists()) {
            return response()->json([
                'erro' => 'Não é possível deletar este produto. Ele possui locações ou histórico de locações.'
            ], 422);
        }*/
        //EntradaDecoracao::where('produto_decoracao_id', $produto->produtos_decoracao_id)->delete();

        $produto->delete();

        return response()->json([
            'mensagem' => 'Produto excluído com sucesso.'
        ], 200);
    }
}
