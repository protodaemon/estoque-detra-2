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



const routes = [
  { 
    path: '/',
    redirect: 'menu'
  },
  { 
    path: '/menu',
    name: 'menu',
    component: MenuPrincipal
  },
  {
    path: '/patrimonio',
    name: 'menu-patrimonio',
    component: MenuPatrimonio
  },
  {
    path:'/estoque-patrimonio',
    name:'EstoquePatrimonio',
    component: EstoquePatrimonio
  },
  {
    path: '/entrada-patrimonio',
    name: 'EntradaPatrimonio',
    component: EntradaPatrimonio
  },
  {
    path: '/consumivel',
    name: 'menu-consumivel',
    component: MenuConsumivel
  },
  {
    path: '/estoque-consumivel',
    name: 'estoque-consumivel',
    component: EstoqueConsumivel
  },
  {
    path: '/entrada-consumivel',
    name: 'entrada-consumivel',
    component: EntradaConsumivel
  },
  {
    path: '/pedidos-consumivel',
    name: 'pedidos',
    component: PedidosConsumivel
  },
  {
    path: '/criar-pedido',
    name: 'criar-pedido',
    component: CriarPedido
  },
  {
    path: '/menu-entradas-consumivel',
    name: 'menu-entradas-consumivel',
    component: MenuEntradasConsumivel
  },
  {
    path: '/menu-saida-consumivel',
    name: 'menu-saida-consumivel',
    component: MenuSaidaConsumivel
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});


export default router;
