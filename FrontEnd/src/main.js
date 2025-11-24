import { createApp } from 'vue';
import './style.css';
import './scrollbar.css';
import App from './App.vue';
import axios from 'axios';
import router from './router';
import * as lucide from 'lucide-vue-next';

const app = createApp(App);

// Registrar ícones
for (const [key, component] of Object.entries(lucide)) {
  app.component(key, component);
}

// 🌐 Base URL global
axios.defaults.baseURL = 'http://localhost:8000/api';
// axios.defaults.baseURL = import.meta.env.VITE_API_URL;

// Inclui token se existir
const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// 🔐 Interceptor: trata erros de autenticação
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      router.push('/login');
    }
    return Promise.reject(error);
  }
);

// Disponibiliza axios global em todos os componentes via this.$axios
app.config.globalProperties.$axios = axios;

app.use(router);
app.mount('#app');
