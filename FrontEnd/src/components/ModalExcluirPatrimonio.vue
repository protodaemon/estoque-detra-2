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
                <span>Nº {{ produto.numero_identificacao }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Mensagem principal -->
        <div class="mb-6">
          <!-- Se tem locações ativas -->
          <div v-if="temLocacoes">
            <div class="flex justify-center mb-4">
              <div class="p-4 bg-amber-100 rounded-full">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">
              Produto não pode ser excluído
            </h3>
            
            <p class="text-gray-600 text-sm leading-relaxed mb-4 text-center">
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

          <!-- Se não tem locações - MOSTRAR CAMPO DE MOTIVO -->
          <div v-else>
            <!-- Aviso -->
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg flex gap-3">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm text-red-800 font-medium mb-1">Atenção!</p>
                <p class="text-xs text-red-700">
                  O produto será removido permanentemente do sistema. Esta movimentação ficará registrada no histórico.
                </p>
              </div>
            </div>

            <!-- Campo de Motivo da Exclusão -->
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Motivo da Exclusão <span class="text-red-500">*</span>
              </label>
              <textarea
                v-model="motivoExclusao"
                placeholder="Ex: Produto danificado, obsoleto, perdido, etc..."
                rows="4"
                class="w-full px-4 py-3 border-2 rounded-xl transition-all resize-none"
                :class="[
                  erro ? 'border-red-300 focus:border-red-400 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-red-400 focus:ring-4 focus:ring-red-100'
                ]"
                @input="erro = ''"
              ></textarea>
              <div class="flex justify-between items-center mt-1">
                <p class="text-xs text-gray-500">Mínimo de 5 caracteres</p>
                <span 
                  class="text-xs"
                  :class="motivoExclusao.length >= 5 ? 'text-green-600 font-medium' : 'text-gray-400'"
                >
                  {{ motivoExclusao.length }} / 500
                </span>
              </div>
              
              <!-- Mensagem de Erro -->
              <p v-if="erro" class="text-sm text-red-600 mt-2 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ erro }}
              </p>
            </div>
          </div>
        </div>

        <!-- Botões de ação -->
        <div class="flex gap-3">
          <button @click="$emit('cancelar')"
            :disabled="excluindo"
            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ temLocacoes ? 'Entendi' : 'Cancelar' }}
          </button>
          
          <button 
            v-if="!temLocacoes"
            :disabled="excluindo || motivoExclusao.trim().length < 5" 
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
      locacoes: [],
      motivoExclusao: '',
      erro: ''
    };
  },
  mounted() {
    this.verificarLocacoes();
  },
  watch: {
    produto() {
      // Resetar o formulário quando trocar de produto
      this.motivoExclusao = '';
      this.erro = '';
    }
  },
  methods: {
    async verificarLocacoes() {
      this.verificandoLocacoes = true;
      
      try {
        // Endpoint para verificar se o produto tem locações ativas
        const response = await axios.get(`/verificar-locacoes-patrimonio/${this.produto.produtos_patrimonio_id}`);
        
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
      // Validar motivo
      if (this.motivoExclusao.trim().length < 5) {
        this.erro = 'O motivo deve ter no mínimo 5 caracteres';
        return;
      }

      if (this.motivoExclusao.trim().length > 500) {
        this.erro = 'O motivo deve ter no máximo 500 caracteres';
        return;
      }

      if (this.temLocacoes) {
        this.erro = 'Não é possível excluir um produto com locações ativas.';
        return;
      }

      this.excluindo = true;
      this.erro = '';
      
      try {
        await axios.delete(`/deletar-patrimonio/${this.produto.produtos_patrimonio_id}`, {
          data: {
            motivo_exclusao: this.motivoExclusao.trim()
          }
        });
        
        this.$emit('excluido');
        this.$emit('cancelar');
        
      } catch (error) {
        console.error("Erro ao excluir produto:", error);
        
        if (error.response?.data?.errors?.motivo_exclusao) {
          this.erro = error.response.data.errors.motivo_exclusao[0];
        } else if (error.response?.status === 422) {
          this.erro = "Não é possível excluir este produto. Verifique se não há locações ativas associadas.";
        } else if (error.response?.data?.message) {
          this.erro = error.response.data.message;
        } else {
          this.erro = "Erro ao excluir produto. Tente novamente.";
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