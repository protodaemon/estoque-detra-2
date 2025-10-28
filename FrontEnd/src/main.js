import { createApp } from 'vue'
import './style.css'
import './scrollbar.css'
import App from './App.vue'
import axios from 'axios'
import router from './router'

// ⬇️ Importa todos os ícones do Lucide
import * as lucide from 'lucide-vue-next'

const app = createApp(App)

// ⬇️ Registra todos os ícones globalmente
for (const [key, component] of Object.entries(lucide)) {
  app.component(key, component)
}

// ⬇️ Configura o Axios
axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = false

const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

app.config.globalProperties.$axios = axios

app.use(router)
app.mount('#app')
