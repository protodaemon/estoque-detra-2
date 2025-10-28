<template>
  <div class="fixed inset-0 backdrop-blur-md bg-pink-200/20 flex items-center justify-center z-50 p-4"
    @click.self="$emit('fechar')">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl relative overflow-hidden transform transition-all">
      <!-- Header com gradiente -->
      <div class="bg-gradient-to-r from-pink-400 to-pink-600 p-6 text-white relative">
        
        <button @click="$emit('fechar')"
          class="absolute top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-all duration-200 z-20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <div class="relative z-10">
          <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-white/20 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold">Nova Categoria</h2>
          </div>
          <p class="text-white/80 text-sm">Crie uma nova categoria para seus produtos</p>
        </div>
      </div>

      <!-- Conteúdo do formulário -->
      <div class="p-6 space-y-5">
        
        <!-- Nome da Categoria -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Nome da Categoria *
          </label>
          <input v-model="categoria.nome" 
            placeholder="Ex: Cadeiras, Mesas, Vasos..." 
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white" />
        </div>

        <!-- Descrição -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            Descrição
          </label>
          <textarea v-model="categoria.descricao" 
            placeholder="Descreva os tipos de produtos desta categoria..." 
            rows="3"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white resize-none"></textarea>
        </div>

      </div>

      <!-- Footer com botões -->
      <div class="p-6 border-t border-gray-100 bg-gray-50/50 flex gap-3">
        <button @click="$emit('fechar')"
          class="flex-1 px-4 py-3 border-2 border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-100 hover:border-gray-300 transition-all duration-200">
          Cancelar
        </button>
        
        <button :disabled="salvando" @click="salvar"
          class="flex-1 bg-gradient-to-r from-pink-400 to-pink-600 text-white font-semibold py-3 rounded-xl hover:from-pink-500 hover:to-pink-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
          <svg v-if="salvando" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          {{ salvando ? 'Salvando...' : 'Criar Categoria' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ModalAdicionarCategoria',
  data() {
    return {
      categoria: {
        nome: '',
        descricao: ''
      },
      salvando: false
    }
  },
  methods: {
    async salvar() {
      if (!this.categoria.nome.trim()) {
        alert("O nome da categoria é obrigatório.");
        return;
      }

      this.salvando = true;
      
      try {
        await axios.post('categorias-consumivel', this.categoria);
        this.$emit('salvou');
        this.categoria = { nome: '', descricao: '' };
        this.$emit('fechar');
      } catch (error) {
        console.error("Erro ao salvar categoria:", error);
        alert("Erro ao salvar categoria.");
      } finally {
        this.salvando = false;
      }
    }
  }
}
</script>