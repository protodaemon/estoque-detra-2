<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">Registrar Entrada - Consumível</h3>
    <!-- Your existing product list + form here -->
    <!-- Reuse the product list and entry form from before -->
    <ProdutoList :produtos="produtos" @select="selectProduto" />
    <EntradaForm v-if="selectedProduto" :produto="selectedProduto" @submit="registrar" />
  </div>
</template>