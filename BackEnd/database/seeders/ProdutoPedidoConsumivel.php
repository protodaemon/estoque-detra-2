<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoPedidoConsumivelSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            [151, 486, 1], [151, 490, 4], [151, 487, 1],
            [152, 510, 2], [152, 531, 2],
            [153, 496, 1], [153, 519, 10], [153, 569, 2],
            [154, 501, 1],
            [155, 566, 1], [155, 552, 3],
        ];

        foreach ($itens as $i) {
            DB::table('produtos_pedido_consumivel')->insert([
                'pedido_consumivel_id' => $i[0],
                'produtos_consumivel_id' => $i[1],
                'quantidade' => $i[2],
            ]);
        }
    }
}