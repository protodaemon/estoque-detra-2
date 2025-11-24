import { createApp } from 'vue';
import './style.css';
import './scrollbar.css';
import App from './App.vue';
import axios from 'axios';
import router from './router';

// Ícones Lucide
import * as lucide from 'lucide-vue-next';

const app = createApp(App);

// Registra ícones globalmente
for (const [key, component] of Object.entries(lucide)) {
  app.component(key, component);
}

// Base URL (dev/prod)
// Em produção prefira usar variável de ambiente (ex: import.meta.env.VITE_API_URL)
axios.defaults.baseURL = 'http://localhost:8000/api';
// axios.defaults.baseURL = 'https://amttdetra.com/estoque_patrimonio/backend/public/api'; //prod

/// ⬇️ Configura o Axios
axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = false

const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      // Token expirado ou inválido → redireciona para login
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

app.config.globalProperties.$axios = axios

app.use(router)
app.mount('#app')
