<template>
  <!-- Header com Breadcrumbs -->
    <div class="bg-white shadow-sm sticky top-0 z-20">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button @click="$router.push('/menu')" class="text-gray-500 hover:text-gray-700 transition-colors">
              <ArrowLeft class="w-5 h-5" />
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Criação de Usuário</h1>
              
              <!-- Breadcrumbs -->
              <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-pink-600 font-medium">Registro</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <div
    class="flex items-center justify-center min-h-[90vh] bg-gradient-to-br from-pink-100 via-pink-50 to-white p-4"
  >
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
      
      <!-- Header -->
      <div class="bg-gradient-to-r from-pink-400 to-pink-600 p-8 text-white text-center relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>
        
        <div class="relative z-10">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
          </div>
          <h2 class="text-3xl font-bold mb-2">Criar Usuário</h2>
          <p class="text-white/80 text-sm">Cadastre um novo usuário para o sistema</p>
        </div>
      </div>

      <div class="p-8">
        <!-- Mensagem de Erro -->
        <div 
          v-if="mensagemErro" 
          class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg animate-pulse"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-red-800">{{ mensagemErro }}</p>
            </div>
            <div class="ml-auto">
              <button
                @click="mensagemErro = ''"
                class="text-red-400 hover:text-red-600 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mensagem de Sucesso -->
        <div 
          v-if="mensagemSucesso" 
          class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg animate-bounce"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">{{ mensagemSucesso }}</p>
            </div>
          </div>
        </div>

        <form @submit.prevent="cadastrar" class="space-y-6">
          
          <!-- Campo Nome -->
          <div class="space-y-2">
            <label for="nome" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Nome Completo
            </label>
            <input
              id="nome"
              v-model="form.nome"
              type="text"
              placeholder="Digite seu nome completo"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required
            />
          </div>

          <!-- Campo Usuário -->
          <div class="space-y-2">
            <label for="user" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Usuário
            </label>
            <input
              id="user"
              v-model="form.user"
              type="text"
              placeholder="Digite seu usuário"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required
            />
          </div>

          <!-- Campo Email -->
          <div class="space-y-2">
            <label for="email_rec" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              Email de Recuperação
            </label>
            <input
              id="email_rec"
              v-model="form.email_rec"
              type="email"
              placeholder="Digite seu email de recuperação"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required
            />
          </div>

          <!-- Campo Senha -->
          <div class="space-y-2">
            <label for="senha" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
              Senha
            </label>
            <input
              id="senha"
              v-model="form.senha"
              type="password"
              placeholder="Digite sua senha"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required
            />
          </div>

          <!-- Botão de Cadastro -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-gradient-to-r from-pink-400 to-pink-600 text-white font-semibold py-4 rounded-xl hover:from-pink-500 hover:to-pink-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg mt-8"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            {{ loading ? 'Cadastrando...' : 'Criar Conta' }}
          </button>

        </form>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'CadastroUsuario',
  data() {
    return {
      form: {
        nome: '',
        user: '',
        email_rec: '',
        senha: ''
      },
      loading: false,
      mensagemErro: '',
      mensagemSucesso: ''
    }
  },
  methods: {
    async cadastrar() {
      this.mensagemErro = '';
      this.mensagemSucesso = '';
      
      // Validação básica
      if (!this.form.nome.trim()) {
        this.mensagemErro = 'O nome é obrigatório';
        return;
      }
      
      if (!this.form.user.trim()) {
        this.mensagemErro = 'O usuário é obrigatório';
        return;
      }
      
      if (!this.form.email_rec.trim()) {
        this.mensagemErro = 'O email de recuperação é obrigatório';
        return;
      }
      
      if (!this.form.senha.trim()) {
        this.mensagemErro = 'A senha é obrigatória';
        return;
      }
      
      if (this.form.senha.length < 6) {
        this.mensagemErro = 'A senha deve ter pelo menos 6 caracteres';
        return;
      }

      this.loading = true;
      
      try {
        const response = await axios.post('/registro', this.form);
        
        this.mensagemSucesso = 'Usuário cadastrado com sucesso!';
        
        // Limpar formulário
        this.form = {
          nome: '',
          user: '',
          email_rec: '',
          senha: ''
        };
        
        // Redirecionar após 2 segundos
        setTimeout(() => {
          this.$router.push('/menu');
        }, 2000);
        
      } catch (error) {
        console.error('Erro ao cadastrar usuário:', error);
        
        if (error.response && error.response.data && error.response.data.message) {
          this.mensagemErro = error.response.data.message;
        } else {
          this.mensagemErro = 'Erro ao cadastrar usuário. Tente novamente.';
        }
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>