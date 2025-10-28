<template>
  <div class="fixed inset-0 backdrop-blur-md bg-red-200/20 flex items-center justify-center z-50 p-4"
    @click.self="$emit('cancelar')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl relative overflow-hidden transform transition-all animate-pulse-scale">
      <!-- Header com gradiente vermelho -->
      <div class="bg-gradient-to-r from-red-500 to-red-700 p-6 text-white relative">
        
        <button @click="$emit('cancelar')"
          class="absolute top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-all duration-200 z-20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <div class="relative z-10">
          <div class="flex items-center gap-3 mb-2">
            <div class="p-3 bg-white/20 rounded-full">
              <svg v-if="!temLocacoes" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold">
                {{ temLocacoes ? 'Não é Possível Excluir' : 'Excluir Produto' }}
              </h2>
              <p class="text-red-100 text-sm">
                {{ temLocacoes ? 'Produto está sendo usado' : 'Esta ação não pode ser desfeita' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Conteúdo do modal -->
      <div class="p-6">
        <!-- Informações do produto -->
        <div class="mb-6">
          <div class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <!-- Foto do produto -->
            <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
              <img v-if="produto.foto" 
                :src="produto.foto"
                :alt="produto.nome"
                class="w-full h-full object-cover" />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
            
            <!-- Detalhes do produto -->
            <div class="flex-1">
              <h3 class="font-semibold text-gray-800 text-lg">{{ produto.nome }}</h3>
              <div class="text-sm text-gray-600 mt-1">
                <span v-if="produto.categoria">{{ produto.categoria.nome }}</span>
                <span class="mx-2">•</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Mensagem principal -->
        <div class="text-center mb-6">
          <!-- Se tem locações ativas -->
          <div v-if="temLocacoes">
            <div class="flex justify-center mb-4">
              <div class="p-4 bg-amber-100 rounded-full">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
              Produto não pode ser excluído
            </h3>
            
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
              Este produto possui <span class="font-semibold text-amber-600">{{ totalLocacoes }} locação(ões) ativa(s)</span>. 
              Para excluí-lo, finalize primeiro todas as locações relacionadas.
            </p>

            <!-- Lista das locações (opcional) -->
            <div v-if="locacoes && locacoes.length > 0" class="bg-amber-50 rounded-lg p-3 text-left">
              <p class="text-sm font-medium text-amber-800 mb-2">Locações ativas:</p>
              <div class="space-y-1">
                <div v-for="locacao in locacoes.slice(0, 3)" :key="locacao.id" 
                  class="text-sm text-amber-700">
                  • Cliente: {{ locacao.cliente_nome }} - {{ formatarData(locacao.data_evento) }}
                </div>
                <div v-if="locacoes.length > 3" class="text-sm text-amber-600">
                  ... e mais {{ locacoes.length - 3 }} locação(ões)
                </div>
              </div>
            </div>
          </div>

          <!-- Se não tem locações -->
          <div v-else>
            <div class="flex justify-center mb-4">
              <div class="p-4 bg-red-100 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
              Deseja excluir este produto?
            </h3>
            
            <p class="text-gray-600 text-sm leading-relaxed">
              O produto será removido permanentemente do seu estoque.
            </p>
          </div>
        </div>

        <!-- Botões de ação -->
        <div class="flex gap-3">
          <button @click="$emit('cancelar')"
            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ temLocacoes ? 'Entendi' : 'Cancelar' }}
          </button>
          
          <button 
            v-if="!temLocacoes"
            :disabled="excluindo" 
            @click="confirmarExclusao"
            class="flex-1 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold py-4 rounded-xl hover:from-red-600 hover:to-red-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg"
          >
            <svg v-if="excluindo" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            {{ excluindo ? 'Excluindo...' : 'Sim, Excluir' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ModalExcluirProduto',
  props: ['produto'],
  data() {
    return {
      excluindo: false,
      verificandoLocacoes: true,
      temLocacoes: false,
      totalLocacoes: 0,
      locacoes: []
    };
  },
  mounted() {
    this.verificarLocacoes();
  },
  methods: {
    async verificarLocacoes() {
      this.verificandoLocacoes = true;
      
      try {
        // Endpoint para verificar se o produto tem locações ativas
        const response = await axios.get(``);
        
        this.temLocacoes = response.data.tem_locacoes;
        this.totalLocacoes = response.data.total || 0;
        this.locacoes = response.data.locacoes || [];
        
      } catch (error) {
        console.error("Erro ao verificar locações:", error);
        // Em caso de erro, assume que não tem locações para permitir a exclusão
        this.temLocacoes = false;
      } finally {
        this.verificandoLocacoes = false;
      }
    },

    async confirmarExclusao() {
      if (this.temLocacoes) {
        alert('Não é possível excluir um produto com locações ativas.');
        return;
      }

      this.excluindo = true;
      
      try {
        await axios.delete(`/deletar-patrimonio/${this.produto.produtos_patrimonio_id}`);
        
        this.$emit('excluido');
        this.$emit('cancelar');
        
      } catch (error) {
        console.error("Erro ao excluir produto:", error);
        
        if (error.response?.status === 422) {
          alert("Não é possível excluir este produto. Verifique se não há locações ativas associadas.");
        } else {
          alert("Erro ao excluir produto. Tente novamente.");
        }
      } finally {
        this.excluindo = false;
      }
    },
    
    formatarPreco(valor) {
      return parseFloat(valor || 0).toFixed(2).replace('.', ',');
    },

    formatarData(data) {
      return new Date(data).toLocaleDateString('pt-BR');
    }
  }
};
</script>

<style scoped>
@keyframes pulse-scale {
  0% {
    transform: scale(0.95);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-pulse-scale {
  animation: pulse-scale 0.2s ease-out;
}
</style>