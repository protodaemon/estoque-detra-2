<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidoConsumivelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pedido_consumivel')->insert([
            ['status' => 'concluido', 'data_pedido' => '2025-10-01', 'data_cancelamento' => null, 'data_alteração' => '2025-10-02', 'observacoes' => 'Pedido urgente para o setor administrativo.'],
            ['status' => 'pendente', 'data_pedido' => '2025-10-10', 'data_cancelamento' => null, 'data_alteração' => null, 'observacoes' => 'Aguardando aprovação da gerência.'],
            ['status' => 'pendente', 'data_pedido' => '2025-10-12', 'data_cancelamento' => null, 'data_alteração' => null, 'observacoes' => 'Verificar disponibilidade de todos os itens antes de aprovar.'],
            ['status' => 'cancelado', 'data_pedido' => '2025-09-20', 'data_cancelamento' => '2025-09-22', 'data_alteração' => null, 'observacoes' => 'Pedido duplicado, cancelado pelo solicitante.'],
            ['status' => 'concluido', 'data_pedido' => '2025-10-05', 'data_cancelamento' => null, 'data_alteração' => null, 'observacoes' => 'Entregar diretamente ao almoxarifado.'],
        ]);
    }
}