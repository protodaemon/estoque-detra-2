<template>
  <div class="fixed inset-0 backdrop-blur-md bg-purple-200/20 flex items-center justify-center z-50 p-4"
    @click.self="$emit('fechar')">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl relative overflow-hidden transform transition-all">
      <!-- Header com gradiente -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white relative">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold">Detalhes do Produto</h2>
          </div>
          <p class="text-white/80 text-sm">Visualização completa das informações</p>
        </div>
      </div>

      <!-- Conteúdo -->
      <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
        
        <!-- Foto do Produto -->
        <div v-if="produto.foto" class="relative rounded-xl overflow-hidden border-2 border-gray-200">
          <img :src="produto.foto" 
            :alt="produto.nome"
            class="w-full h-64 object-contain bg-gray-50" />
        </div>
        <div v-else class="relative rounded-xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 h-64 flex items-center justify-center">
          <div class="text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 text-sm">Sem foto</p>
          </div>
        </div>

        <!-- Descrição -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
          <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Descrição</span>
          </div>
          <p class="text-gray-600 leading-relaxed">
            {{ produto.descricao || 'Sem descrição' }}
          </p>
        </div>

        <!-- Grid de Informações -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <!-- Número de Identificação -->
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
              </svg>
              <span class="text-sm font-semibold text-blue-900">Número de Identificação</span>
            </div>
            <p class="text-2xl font-bold text-blue-700">{{ produto.numero_identificacao || 'N/A' }}</p>
          </div>

          <!-- Categoria -->
          <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              <span class="text-sm font-semibold text-purple-900">Categoria</span>
            </div>
            <p class="text-lg font-bold text-purple-700">{{ getNomeCategoria(produto.categoria_patrimonio) }}</p>
          </div>

          <!-- Localização -->
          <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl border border-amber-200 md:col-span-2">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0L6.343 16.657a8 8 0 1111.314 0zM12 10a2 2 0 110-4 2 2 0 010 4z"></path>
              </svg>
              <span class="text-sm font-semibold text-amber-900">Localização</span>
            </div>
            <p class="text-lg font-bold text-amber-700">{{ getNomeLocalizacao(produto.localizacao_id) }}</p>
          </div>
        </div>

    

        <!-- Observações -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
          <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Observações</span>
          </div>
          <p class="text-gray-600 leading-relaxed">
            {{ produto.observacoes || 'Sem observações' }}
          </p>
        </div>

        <!-- Botão de Ação -->
        <div class="pt-4 border-t border-gray-200">
          <button @click="$emit('fechar')"
            class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-3 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Fechar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalVisualizarPatrimonio',
  props: {
    produto: {
      type: Object,
      required: true
    },
    categorias: {
      type: Array,
      default: () => []
    },
    localizacoes: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    getNomeCategoria(id) {
      const categoria = this.categorias.find(c => c.categoria_patrimonio_id === id);
      return categoria ? categoria.nome : 'N/A';
    },
    getNomeLocalizacao(id) {
      const localizacao = this.localizacoes.find(l => l.localizacao_id === id);
      return localizacao ? localizacao.nome : 'N/A';
    }
  }
};
</script>