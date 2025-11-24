<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 via-blue-50 to-white p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-8 text-white text-center relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>

        <div class="relative z-10">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <h2 class="text-3xl font-bold mb-2">Bem-vindo!</h2>
          <p class="text-white/80 text-sm">Faça login para acessar o sistema</p>
        </div>
      </div>

      <div class="p-8">
        <!-- Mensagem de Erro -->
        <div v-if="mensagemErro" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                </path>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-red-800">{{ mensagemErro }}</p>
            </div>
            <div class="ml-auto">
              <button @click="mensagemErro = ''" class="text-red-400 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mensagem de Sucesso -->
        <div v-if="mensagemSucesso" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">{{ mensagemSucesso }}</p>
            </div>
          </div>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">

          <!-- Campo Usuário -->
          <div class="space-y-2">
            <label for="user" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Usuário
            </label>
            <input id="user" v-model="user" type="text" placeholder="Digite seu usuário"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required />
          </div>

          <!-- Campo Senha -->
          <div class="space-y-2">
            <label for="password" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
              </svg>
              Senha
            </label>
            <input id="password" v-model="password" type="password" placeholder="Digite sua senha"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-200 bg-gray-50 hover:bg-white"
              required />
          </div>

          <!-- Botão de Login -->
          <button type="submit" :disabled="loading"
            class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold py-4 rounded-xl hover:from-blue-500 hover:to-blue-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg mt-8">
            <svg v-if="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
            </svg>
            {{ loading ? 'Entrando...' : 'Entrar no Sistema' }}
          </button>

        </form>
      </div>

    </div>
  </div>
</template>

<script>
import AuthService from '../services/auth';

export default {
  name: 'LoginPage',
  data() {
    return {
      user: '',
      password: '',
      loading: false,
      mensagemErro: '',
      mensagemSucesso: '',
    };
  },
  methods: {
    async handleLogin() {
      this.mensagemErro = '';
      this.mensagemSucesso = '';
      this.loading = true;

      try {
        await AuthService.login({ user: this.user, password: this.password });

        this.mensagemSucesso = 'Login realizado com sucesso! Redirecionando...';

        setTimeout(() => {
          this.$router.push('/menu');
        }, 1500);

      } catch (error) {
        console.error('Erro ao fazer login:', error);

        // ✅ MELHORIA: Tratamento baseado no code que vem diretamente
        const code = error.code || error.response?.data?.code;
        const errorMessage = error.error || error.response?.data?.error;
        const status = error.response?.status;

        if (code === 'USER_NOT_FOUND') {
          this.mensagemErro = 'Usuário não encontrado.';
        }
        else if (code === 'INVALID_PASSWORD') {
          this.mensagemErro = 'Senha incorreta.';
        }
        else if (code === 'TOKEN_ERROR' || status === 500) {
          this.mensagemErro = 'Erro no servidor. Tente novamente.';
        }
        else if (status === 404) {
          this.mensagemErro = 'Usuário não encontrado.';
        }
        else if (status === 401) {
          this.mensagemErro = 'Credenciais inválidas.';
        }
        else if (status >= 500) {
          this.mensagemErro = 'Erro no servidor. Tente novamente.';
        }
        else if (error.code === 'ERR_NETWORK' || !error.response) {
          this.mensagemErro = 'Erro de conexão. Verifique sua internet.';
        }
        else {
          this.mensagemErro = errorMessage || 'Erro inesperado. Tente novamente.';
        }

        setTimeout(() => {
          this.mensagemErro = '';
        }, 5000);

      } finally {
        this.loading = false;
      }
    }
  }
};
</script>