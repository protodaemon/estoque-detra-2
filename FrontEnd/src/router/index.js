import { createRouter, createWebHistory } from 'vue-router';

import MenuPrincipal from '@/pages/MenuPrincipal.vue'
import MenuPatrimonio from '../pages/MenuPatrimonio.vue';
import EstoquePatrimonio from '../pages/EstoquePatrimonio.vue';
import EntradaPatrimonio from '../pages/EntradaPatrimonio.vue';
import MenuConsumivel from '../pages/MenuConsumivel.vue';
import EstoqueConsumivel from '../pages/EstoqueConsumivel.vue';
import EntradaConsumivel from '../pages/EntradaConsumivel.vue';
import PedidosConsumivel from '../pages/PedidosConsumivel.vue';
import CriarPedido from '../pages/CriarPedido.vue';
import MenuEntradasConsumivel from '../pages/MenuEntradasConsumivel.vue';
import MenuSaidaConsumivel from '../pages/MenuSaidaConsumivel.vue';
import MenuPedidosConsumivel from '../pages/MenuPedidosConsumivel.vue';
import ConsultaPedidos from '../pages/ConsultaPedidos.vue';
import LoginPage from '@/pages/LoginPage.vue';
import RegistroPage from '@/pages/RegistroPage.vue';
import AcessoNegado from '@/pages/AcessoNegado.vue';
import axios from 'axios'



const routes = [
  { 
    path: '/',
    redirect: 'login'
  },
  {
    path:'/login',
    name:'login',
    component: LoginPage,
  },
   {
    path:'/cadastrar',
    name:'RegistroPage',
    component: RegistroPage,
  },
  { 
    path: '/menu',
    name: 'menu',
    component: MenuPrincipal,
    meta: {requiresAuth: true}
  },
  {
    path: '/patrimonio',
    name: 'menu-patrimonio',
    component: MenuPatrimonio,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path:'/estoque-patrimonio',
    name:'EstoquePatrimonio',
    component: EstoquePatrimonio,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/entrada-patrimonio',
    name: 'EntradaPatrimonio',
    component: EntradaPatrimonio,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/consumivel',
    name: 'menu-consumivel',
    component: MenuConsumivel,
    meta: {requiresAuth: true}
  },
  {
    path: '/estoque-consumivel',
    name: 'estoque-consumivel',
    component: EstoqueConsumivel,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/entrada-consumivel',
    name: 'entrada-consumivel',
    component: EntradaConsumivel,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/pedidos-consumivel',
    name: 'pedidos',
    component: PedidosConsumivel,
    meta: {requiresAuth: true}
  },
  {
    path: '/criar-pedido',
    name: 'criar-pedido',
    component: CriarPedido,
    meta: {requiresAuth: true},
  },
  {
    path: '/menu-entradas-consumivel',
    name: 'menu-entradas-consumivel',
    component: MenuEntradasConsumivel,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/menu-saida-consumivel',
    name: 'menu-saida-consumivel',
    component: MenuSaidaConsumivel,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/menu-pedidos',
    name: 'menu-pedidos',
    component: MenuPedidosConsumivel,
    meta: {requiresAuth: true}
  },
  {
    path: '/consultar-pedido',
    name: 'consultar-pedido',
    component: ConsultaPedidos,
    meta: { 
      requiresAuth: true,
      role: 'admin' // Apenas admin
    }
  },
  {
    path: '/acesso-negado',
    name: 'acesso-negado',
    component: AcessoNegado,
    meta: { requiresAuth: true }
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

function getUser() {
  const userStr = localStorage.getItem('user');
  return userStr ? JSON.parse(userStr) : null;
}


router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');
  const userStr = localStorage.getItem('user');
  const currentUser = userStr ? JSON.parse(userStr) : null;

  // Se a rota exige autenticação
  if (to.meta.requiresAuth) {
    // Se não há token → manda pro login
    if (!token) {
      return next({ name: 'login' });
    }

    if (to.meta.role === 'admin') {
      // Se o usuário não tiver nivel_acesso ou não for admin
      if (!currentUser || currentUser.nivel_acesso !== 'admin') {
        // Redireciona para uma página padrão (ex: acesso negado) ou exibe erro
        return next({ name: 'acesso-negado' });
      }
    }

    try {
      // Tenta validar o token no backend
      await axios.get('/me', {
        headers: { Authorization: `Bearer ${token}` },
      });
      return next(); // Token válido → segue
    } catch (error) {
      console.warn('Token inválido:', error.response?.data || error.message);
      localStorage.removeItem('token'); // limpa token inválido
      return next({ name: 'login' });
    }
  }

  next(); // Se a rota não exige login, segue
});

export default router;
