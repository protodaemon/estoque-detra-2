<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 via-blue-50 to-white p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative">
      
      <!-- Header (Vermelho para indicar erro/bloqueio) -->
      <div class="bg-gradient-to-r from-red-400 to-red-600 p-8 text-white text-center relative overflow-hidden">
        <!-- Elementos decorativos de fundo (iguais ao login) -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>

        <div class="relative z-10">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4 animate-pulse">
            <!-- Ícone de Cadeado / Bloqueio -->
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
              </path>
            </svg>
          </div>
          <h2 class="text-3xl font-bold mb-2">Acesso Negado</h2>
          <p class="text-white/90 text-sm font-medium">Área restrita apenas para administradores.</p>
        </div>
      </div>

      <!-- Conteúdo -->
      <div class="p-8 text-center space-y-6">
        
        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg text-left">
          <p class="text-gray-700 text-sm">
            Você não possui as permissões necessárias para acessar esta rota. Se você acredita que isso é um erro, contate o suporte.
          </p>
        </div>

        <!-- Botão Voltar (Azul para indicar ação segura) -->
        <button 
          @click="voltar" 
          class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-500 hover:to-blue-700 transition-all duration-200 transform hover:scale-[1.02] shadow-lg flex items-center justify-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Voltar para o Menu
        </button>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AcessoNegado',
  methods: {
    voltar() {
      
      this.$router.go(-1);
    }
  }
}
</script>