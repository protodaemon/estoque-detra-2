import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api'; // URL do backend Laravel
//mudar pra prod

// 🔧 cria uma instância Axios própria (com interceptores)
const api = axios.create({
  baseURL: API_BASE_URL,
});

// 🔐 Interceptor: adiciona o token JWT em todas as requisições
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => Promise.reject(error));

const AuthService = {
  /**
   * 🔹 LOGIN DO USUÁRIO
   */
  async login(credentials) {
    try {
      const response = await api.post('/login', {
        user: credentials.user,
        senha: credentials.password, // o backend espera "senha"
      });

      // ⚠️ Verifique o nome do campo retornado pelo backend
      // (pode ser "access_token" ou "token")
      const token = response.data.access_token || response.data.token;

      if (token) {
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(response.data.user || {}));

        // Define imediatamente o header padrão do Axios
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }

      return response.data;
    } catch (error) {
      console.error('Erro no login:', error.response?.data || error.message);
      throw error.response?.data || { message: 'Erro ao fazer login' };
    }
  },

  /**
   * 🔹 REGISTRO DE NOVO USUÁRIO
   */
  async register(userData) {
    try {
      const response = await api.post('/registro', userData);
      return response.data;
    } catch (error) {
      throw error.response?.data || error.message;
    }
  },

  /**
   * 🔹 LOGOUT DO USUÁRIO
   */
  async logout() {
    try {
      const token = localStorage.getItem('token');
      if (!token) throw new Error('Nenhum token encontrado');

      await api.post('/logout', {}, {
        headers: { Authorization: `Bearer ${token}` },
      });

      localStorage.removeItem('token');
      localStorage.removeItem('user');
    } catch (error) {
      console.error('Erro no logout:', error);
      throw error.response?.data || error.message;
    }
  },

  /**
   * 🔹 UTILITÁRIOS
   */
  getCurrentUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  },

  getToken() {
    return localStorage.getItem('token');
  },

  isAuthenticated() {
    return !!this.getToken();
  },

  /**
   * 🔹 Exporta a instância configurada do Axios
   * Assim, outros serviços (estatísticas, estoque etc.)
   * podem reutilizar o mesmo Axios com token embutido.
   */
  api,
};

export default AuthService;
