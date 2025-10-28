<template>
  <div
    class="fixed bottom-6 right-6 bg-white shadow-xl rounded-xl px-6 py-3 flex items-center gap-2 select-none border border-gray-100"
  >
    <!-- Botão Anterior -->
    <button
      @click="mudarPagina(paginaAtual - 1)"
      :disabled="paginaAtual === 1"
      class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 transition-all duration-200 text-sm"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
      Anterior
    </button>

    <!-- Separador visual -->
    <div class="w-px h-6 bg-gray-200 mx-1"></div>

    <!-- Botões numéricos -->
    <div class="flex items-center gap-1">
      <button
        v-for="p in paginasVisiveis"
        :key="p"
        @click="mudarPagina(p)"
        :class="[
          'w-10 h-10 rounded-lg font-medium text-sm transition-all duration-200 flex items-center justify-center',
          p === paginaAtual 
            ? 'bg-blue-600 text-white shadow-md transform scale-105' 
            : 'bg-gray-100 text-gray-700 hover:bg-blue-100 hover:text-blue-600 hover:scale-105'
        ]"
      >
        {{ p }}
      </button>
    </div>

    <!-- Separador visual -->
    <div class="w-px h-6 bg-gray-200 mx-1"></div>

    <!-- Botão Próximo -->
    <button
      @click="mudarPagina(paginaAtual + 1)"
      :disabled="paginaAtual === totalPaginas"
      class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 transition-all duration-200 text-sm"
    >
      Próximo
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    paginaAtual: {
      type: Number,
      required: true,
    },
    totalPaginas: {
      type: Number,
      required: true,
    },
    maxPaginasVisiveis: {
      type: Number,
      default: 5,
    },
  },
  emits: ['atualizar-pagina'],
  computed: {
    paginasVisiveis() {
      // Mostra um máximo de maxPaginasVisiveis números ao redor da paginaAtual
      const total = this.totalPaginas;
      const maxVisiveis = this.maxPaginasVisiveis;
      let inicio = Math.max(this.paginaAtual - Math.floor(maxVisiveis / 2), 1);
      let fim = inicio + maxVisiveis - 1;
      if (fim > total) {
        fim = total;
        inicio = Math.max(fim - maxVisiveis + 1, 1);
      }
      const paginas = [];
      for (let i = inicio; i <= fim; i++) {
        paginas.push(i);
      }
      return paginas;
    },
  },
  methods: {
    mudarPagina(novaPagina) {
      if (novaPagina >= 1 && novaPagina <= this.totalPaginas && novaPagina !== this.paginaAtual) {
        this.$emit('atualizar-pagina', novaPagina);
      }
    },
  },
};
</script>


