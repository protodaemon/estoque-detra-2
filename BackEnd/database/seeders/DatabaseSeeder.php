<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategoriaConsumivelSeeder::class
            ,ProdutoConsumivelSeeder::class
            ,PedidoConsumivelSeeder::class
            ,ProdutoPedidoConsumivelSeeder::class
            // SaidaConsumivelSeeder::class,
            // EntradaConsumivelSeeder::class,
        ]);
    }
}