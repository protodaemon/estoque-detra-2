<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header/Breadcrumb -->
    <div class="bg-white shadow-sm sticky top-0 z-20">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="text-gray-500 hover:text-gray-700">
              <ArrowLeft class="w-5 h-5" />
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Estoque Consumível</h1>
              <div class="flex items-center text-sm text-gray-600 mt-1">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4 mx-1" />
                <span class="text-gray-400">Menu Consumível</span>
                <ChevronRight class="w-4 h-4 mx-1" />
                <span>Estoque Consumível</span>
                <ChevronRight class="w-4 h-4 mx-1" />
                <span class="text-blue-600">{{ categoriaSelecionada?.nome || 'Todos' }}</span>
              </div>
            </div>
          </div>
          
          <!-- Stats resumo -->
          <div class="flex items-center space-x-4 text-sm">
            <div class="bg-green-50 px-3 py-1 rounded-full">
              <span class="text-green-700 font-medium">{{ total }} produtos</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex">
      <!-- Sidebar -->
      <div class="w-64 bg-white shadow-sm h-[calc(100vh-80px)] overflow-y-auto sticky top-20">
        <div class="p-6">
          <!-- Botão Nova Categoria -->
          <button 
            @click="mostrarModal = true"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2"
          >
            <Plus class="w-4 h-4" />
            <span>Nova Categoria</span>
          </button>


          <!-- Search Categorias -->
          <div class="mt-4 relative">
            <Search class="w-4 h-4 absolute left-3 top-3 text-gray-400" />
            <input
              v-model="filtro"
              type="text"
              placeholder="Buscar categoria..."
              class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Lista de Categorias -->
          <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Categorias</h3>
            
            <!-- Todas as categorias -->
            <button
              @click="selecionarCategoria(null)"
              :class="[
                'w-full flex items-center justify-between px-3 py-2 rounded-lg text-left transition-colors mb-1',
                !categoriaSelecionada ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-50'
              ]"
            >
              <div class="flex items-center space-x-3">
                <Folder class="w-4 h-4" />
                <span>Todas</span>
              </div>
            </button>

            <!-- Categorias filtradas -->
            <button
              v-for="categoria in categoriasFiltradas"
              :key="categoria.categoria_consumivel_id"
              @click="selecionarCategoria(categoria)"
              :class="[
                'w-full flex items-center justify-between px-3 py-2 rounded-lg text-left transition-colors mb-1',
                categoriaSelecionada?.categoria_consumivel_id === categoria.categoria_consumivel_id ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-50'
              ]"
            >
              <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                <span>{{ categoria.nome }}</span>
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="flex-1 p-6">
        <!-- Barra de Ferramentas -->
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-6">
            <h2 class="text-xl font-semibold text-gray-800">
              {{ categoriaSelecionada?.nome || 'Todos os Produtos' }}
            </h2>
            
            <!-- Toggle Ativo/Inativo estilo abas -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
              <div class="flex">
                <button
                  @click="filtroStatus = 'ativo', carregarProdutos()"
                  :class="[
                    'px-4 py-2 text-sm font-medium transition-colors border-b-2',
                    filtroStatus === 'ativo' 
                      ? 'text-green-600 border-green-500 bg-green-200' 
                      : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
                  ]">
                  Ativo
                </button>
                
                <button
                  @click="filtroStatus = 'inativo', carregarProdutos()"
                  :class="[
                    'px-4 py-2 text-sm font-medium transition-colors border-b-2',
                    filtroStatus === 'inativo' 
                      ? 'text-red-600 border-red-500 bg-red-200' 
                      : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
                  ]"
                >
                  Inativo
                </button>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
              <Search class="w-4 h-4 absolute left-3 top-3 text-gray-400" />
              <input
                v-model="buscaNome"
                type="text"
                placeholder="Buscar por nome ou id..."
                class="pl-9 pr-4 py-2 w-80 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>

            <!-- Botão pra baixar relatório -->
            <button 
            @click="generatePDF" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center space-x-2">
              📄 Gerar Relatório
            </button>

            <!-- Botão Adicionar -->
            <button
              @click="mostrarModalProduto = true"
              class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center space-x-2"
            >
              <Plus class="w-4 h-4" />
              <span>Adicionar Produto</span>
            </button>
          </div>
        </div>

        <ModalAdicionarConsumivel v-if="mostrarModalProduto" :categorias="categorias" @fechar="mostrarModalProduto = false"
          @salvou="carregarProdutos(categoriaSelecionada?.categoria_consumivel_id, currentPage)" />
          <ModalAdicionarCategoriaConsumivel v-if="mostrarModal" @fechar="mostrarModal = false" @salvou="categoriaSalva" />
          <Loading v-if="loadingControl"/>
          <ModalEditarConsumivel v-if="mostrarModalEdicao" :categorias="categorias" :produto="produtoSelecionado" @fechar="mostrarModalEdicao = false" @atualizado="handleProdutoAtualizado" />
          <ModalExcluirConsumivel v-if="mostrarModalExclusao" :produto="produtoParaExcluir" @cancelar="cancelarExclusao" @excluido="handleProdutoExcluido" />


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8 pb-10">
  <!-- Cards de Produtos -->
  <div
    v-for="peca in this.pecas"
    :key="peca.produtos_consumivel_id"
    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group"
  >
    <!-- Imagem -->
    <div class="relative h-48 bg-gray-100">
      <img
        v-if="peca.foto"
        :src="peca.foto"
        :alt="peca.nome"
        class="w-full h-full object-contain"
      />
      <div v-else class="w-full h-full flex items-center justify-center">
        <Image class="w-12 h-12 text-gray-400" />
      </div>
      
      <!-- Status Badge -->
      <div :class="[
        'absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-medium',
        getStatusBadge(peca).class
      ]">
        {{ getStatusBadge(peca).text }}
      </div>

      <!-- Botões de Ação com Tooltips -->
      <div class="absolute top-2 left-2">
        <div class="relative">
          <!-- Botão dos 3 pontinhos -->
          <button
            @click="toggleDropdown(peca.produtos_consumivel_id)"
            class="p-2 bg-white/90 hover:bg-white text-gray-600 hover:text-gray-800 rounded-lg shadow-sm transition-all duration-200 hover:scale-105"
          >
            <MoreVertical class="w-4 h-4" />
          </button>

          <!-- Dropdown Menu -->
          <div 
            v-if="dropdownAberto === peca.produtos_consumivel_id"
            class="absolute top-full left-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 p-1 flex gap-1 z-20"
          >
            <!-- Editar -->
            <div class="relative group/tooltip">
              <button
                @click="abrirModalEdicao(peca); fecharDropdown()"
                class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 hover:scale-105"
              >
                <Edit3 class="w-4 h-4" />
              </button>
              <!-- Tooltip -->
              <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover/tooltip:opacity-100 pointer-events-none transition-opacity duration-200 z-30">
                <div class="bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                  Editar
                  <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                </div>
              </div>
            </div>

            <!-- Ativar/Desativar -->
            <div class="relative group/tooltip">
              <button
                @click="toggleStatusProduto(peca, peca.ativo === 1 ? 0 : 1); fecharDropdown()"
                class="p-2 rounded-lg transition-all duration-200 hover:scale-105"
                :class="peca.ativo ? 'text-amber-600 hover:text-amber-700 hover:bg-amber-50' : 'text-green-600 hover:text-green-700 hover:bg-green-50'"
              >
                <EyeOff v-if="peca.ativo" class="w-4 h-4" />
                <Eye v-else class="w-4 h-4" />
              </button>
              <!-- Tooltip -->
              <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover/tooltip:opacity-100 pointer-events-none transition-opacity duration-200 z-30">
                <div class="bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                  {{ peca.ativo ? 'Desativar' : 'Reativar' }}
                  <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                </div>
              </div>
            </div>

            <!-- Excluir -->
            <div class="relative group/tooltip">
              <button
                @click="confirmarExclusao(peca); fecharDropdown()"
                class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 hover:scale-105"
              >
                <Trash2 class="w-4 h-4" />
              </button>
              <!-- Tooltip -->
              <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover/tooltip:opacity-100 pointer-events-none transition-opacity duration-200 z-30">
                <div class="bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                  Excluir
                  <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Conteúdo -->
    <div class="p-4">
      <h3 class="font-semibold text-gray-800 mb-2 truncate">{{ peca.nome }}</h3>
      
      <!-- Stats -->
      <div class="space-y-2 text-sm mb-3">  
        <div class="flex items-center gap-2">
          <span class="text-gray-500">Quantidade: </span>
          <span class="text-green-600 font-medium">{{ peca.quantidade}}</span>
        </div>
      </div>
    </div>
  </div>
</div>

        <!-- Estado vazio -->
        <div v-if="this.pecas.length === 0" class="text-center py-12">
          <Image class="w-16 h-16 text-gray-400 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
          <p class="text-gray-500 mb-4">
            {{ buscaNome ? 'Tente ajustar sua busca.' : 'Comece adicionando seu primeiro produto.' }}
          </p>
          <button
            @click="mostrarModalProduto = true"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors"
          >
            Adicionar Produto
          </button>
        </div>

        <!-- Paginação (mantida original) -->
        <Paginacao :paginaAtual="currentPage" :totalPaginas="lastPage" @atualizar-pagina="mudarPagina" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import ModalAdicionarCategoriaConsumivel from '@/components/ModalAdicionarCategoriaConsumivel.vue'
import ModalAdicionarConsumivel from '@/components/ModalAdicionarConsumivel.vue'
import Paginacao from '@/components/Paginacao.vue'
import { Folder, Plus, Search, Image, ArrowLeft, ChevronRight, Edit3, EyeOff, Eye, Trash2, MoreVertical } from 'lucide-vue-next'
import Loading from '../components/Loading.vue'
import ModalEditarConsumivel from '../components/ModalEditarConsumivel.vue'
import ModalExcluirConsumivel from '../components/ModalExcluirConsumivel.vue'
import jsPDF from 'jspdf';
//import { reactive } from 'vue'
import autoTable from 'jspdf-autotable';
//import 'jspdf-autotable';
import imgPdf from '@/assets/smip.png';

/* 
DEBOUNCE FORA DO METHODS
Garante que ela não dependa do ciclo de vida do componente.
  */
function debounce(fn, delay = 500) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn.apply(this, args), delay);
  };
}

export default {
  name: "EstoqueConsumivel",
  components: {
    ModalAdicionarCategoriaConsumivel,
    ModalAdicionarConsumivel,
    ModalEditarConsumivel,
    ModalExcluirConsumivel,
    Paginacao,
    Folder,
    Plus,
    Search,
    Image,
    ArrowLeft,
    ChevronRight,
    Loading,
    Edit3,
    EyeOff,
    Eye,
    Trash2,
    MoreVertical,
  },
  data() {
    return {
      filtro: "",
      categorias: [],
      pecas: [],
      mostrarModal: false,
      mostrarModalProduto: false,
      categoriaSelecionada: null,
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 12,
      buscaNome: "",
      mostrarModalEdicao: false,
      produtoSelecionado: null,
      mostrarModalExclusao: false,
      produtoParaExcluir: null,
      loadingControl: false,
      filtroStatus: 'ativo',
      dropdownAberto: null,
      totalEstoqueContagem: 0,
      menuExpansivel: null,
      debouncedBuscarProdutos: () => { },
    };
  },
  computed: {
    categoriasFiltradas() {
      if (!this.filtro.trim()) return this.categorias;
      const termo = this.filtro.toLowerCase();
      return this.categorias.filter((cat) =>
        cat.nome.toLowerCase().includes(termo)
      );
    },
  },
  mounted() {
    this.carregarCategorias();
    this.carregarProdutos();
    this.totalEstoque();
    this.debouncedBuscarProdutos = debounce(() => {
      this.carregarProdutos(this.categoriaSelecionada?.categoria_consumivel_id, 1);
    }, 500);
  },
  methods: {
    async generatePDF() {
      if (this.isLoading) {
        alert("Espere o carregamento dos dados.");
        return;
      }

      try {
        // 🔹 Chama backend para buscar todos ativos
        
        const response = await axios.get('/consumivel-ativos');
        const consumivelAtivos = response.data;

        const doc = new jsPDF('p', 'mm', 'a4');
        
        
        doc.addImage(imgPdf, "PNG", 10, 0, 200, 40);
        

        const tituloRelatorio = "RELATÓRIO DE PRODUTOS CONSUMÍVEIS ATIVOS";

        var larguraDoTexto = doc.getTextWidth(tituloRelatorio);
        var x = (210 - larguraDoTexto) / 2;

        doc.setFontSize(14);
        doc.text(tituloRelatorio, x, 50);

        // Data de geração
        const dataAtual = new Date().toLocaleDateString('pt-BR', { 
          day: '2-digit', month: '2-digit', year: 'numeric',
          hour: '2-digit', minute: '2-digit'
        });
        doc.setFontSize(10);
        doc.text(`Gerado em: ${dataAtual}`, 10, 60);

        // Colunas
        const columns = [
          { header: 'ID', dataKey: 'numero_identificacao' },
          { header: 'Nome', dataKey: 'nome' },
          { header: 'Descrição', dataKey: 'descricao' },
        ];

        /*doc.autoTable({
          columns,
          body: consumivelAtivos,
          startY: 70,
          margin: { top: 10, right: 10, bottom: 5, left: 10 },
          headStyles: { fillColor: [41, 128, 185] },
          didDrawPage: (data) => {
            const numeroDaPagina = doc.internal.getNumberOfPages();
            const string = `Página ${numeroDaPagina}`;
            doc.setFontSize(8);
            doc.text(string, data.settings.margin.left, doc.internal.pageSize.getHeight() - 3);
          }
        });*/

        autoTable(doc, {
        columns: columns,
        body: consumivelAtivos,
        startY: 70,
        margin: { top: 10, right: 10, bottom: 5, left: 10 },
        didDrawPage: (data) => {
          const numeroDaPagina = doc.internal.getNumberOfPages();
          const total = consumivelAtivos.length;

          const string = "Página " + numeroDaPagina + " | Total de registros: " + total;
          doc.setFontSize(8);
          doc.text(string, data.settings.margin.left, doc.internal.pageSize.getHeight() - 3);
        }
      });

        window.open(doc.output('bloburl'), '_blank');
      } catch (error) {
        console.error("Erro ao gerar relatório:", error);
        alert("Erro ao gerar relatório. Veja o console.");
      }
    },

    async totalEstoque(){
      try{
        const response = await axios.get('/total-estoque')
        this.totalEstoqueContagem = response.data.total_estoque;
      }catch(error){
        console.error("Erro ao somar itens do estoque:", error);
      }
    }, 
    async carregarCategorias() {
      try {
        const response = await axios.get('/categorias-consumivel');
        this.categorias = response.data;
      } catch (error) {
        console.error("Erro ao carregar categorias:", error);
      }
    },
    async carregarProdutos(categoriaId = null, page = 1) {
      console.log(this.loadingControl)
      this.loadingControl = true;
      try {
        const params = {
          page: page,
          per_page: this.perPage,
        };

        if (categoriaId) {
          params.categoria = categoriaId;
        }

        if (this.buscaNome.trim()) {
          params.nome = this.buscaNome.trim();
        }

        if(this.filtroStatus === "ativo"){
          params.ativo = 1;
        } else if(this.filtroStatus === "inativo"){
          params.ativo = 0;
        }

        const response = await axios.get('/produtos-consumivel', { params });

        this.pecas = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
        this.total = response.data.total;
        console.log(this.total);
        console.log(this.pecas);
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      }
        this.loadingControl = false;
    },
    categoriaSalva() {
      this.mostrarModal = false;
      this.carregarCategorias();
    },
    selecionarCategoria(categoria) {
      this.categoriaSelecionada = categoria;
      this.currentPage = 1;
      if (categoria) {
        this.carregarProdutos(categoria.categoria_consumivel_id, 1);
      } else {
        this.carregarProdutos(null, 1);
      }
    },
    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.carregarProdutos(this.categoriaSelecionada?.categoria_consumivel_id, this.currentPage);
      }
    },
    getStatusBadge(produto) {
      if (produto.qtd_disponivel === 0) {
        return { text: 'Sem Estoque', class: 'bg-red-100 text-red-700' }
      } else if (produto.qtd_disponivel <= 10) {
        return { text: 'Estoque Baixo', class: 'bg-yellow-100 text-yellow-700' }
      } else {
        return { text: 'Disponível', class: 'bg-green-100 text-green-700' }
      }
    },
    formatarPreco(valor) {
      return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })
    },
    handleProdutoAtualizado() {
      this.carregarProdutos(this.categoriaSelecionada?.categoria_consumivel_id, this.currentPage);
      this.totalEstoque();
      this.mostrarModalEdicao = false;
      this.produtoSelecionado = null;
    },
    abrirModalEdicao(produto){
      this.produtoSelecionado = {...produto};
      console.log(this.produtoSelecionado)
      this.mostrarModalEdicao = true
    },
    fecharModalEdicao(){
      this.mostrarModalEdicao = false;
      this.produtoSelecionado = null;
    },
    confirmarExclusao(produto){
      this.produtoParaExcluir = produto;
      this.mostrarModalExclusao = true;
    },
    cancelarExclusao(){
      this.mostrarModalExclusao = false;
      this.produtoParaExcluir = null;
    },
    handleProdutoExcluido(){
      this.carregarProdutos(this.categoriaSelecionada?.categoria_consumivel_id, this.currentPage);
      this.totalEstoque();

      this.mostrarModalExclusao = false;
      this.produtoParaExcluir = null;

      console.log("produto excluido")
    },
    toggleMenuExpansivel(produtoId) {
    this.menuExpansivel = this.menuExpansivel === produtoId ? null : produtoId;
  },
  
  async toggleStatusProduto(peca, novoStatus) {
    this.menuExpansivel = null;
    console.log("ID: ", peca.produtos_consumivel_id)
    console.log("status atual: ", peca.ativo)
    console.log("Novo status: ", novoStatus)
    try{
      const response = await axios.put(`/mudar-ativo-consumivel/${peca.produtos_consumivel_id}`, {
        ativo: novoStatus
      })
      console.log(response.data)
      this.carregarProdutos();
    }catch(error){
      console.error("Erro ao atualizar status", error)
    }
  },
  toggleDropdown(produtoId) {
    if (this.dropdownAberto === produtoId) {
      this.dropdownAberto = null;
    } else {
      this.dropdownAberto = produtoId;
    }
  },
  
  fecharDropdown() {
    this.dropdownAberto = null;
  },

  },
  watch: {
    buscaNome() {
      if (typeof this.debouncedBuscarProdutos === 'function') {
        this.debouncedBuscarProdutos();
      }
    }
  },
};
</script>

<style scoped>
/* Mantém estilos customizados se precisar */
</style>