<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaConsumivelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria_consumivel')->insert([
            ['nome' => 'Eletrônicos', 'descricao' => 'Notebooks, projetores, monitores e outros dispositivos eletrônicos.'],
            ['nome' => 'Mobiliário de Escritório', 'descricao' => 'Mesas, cadeiras, armários e estantes.'],
            ['nome' => 'Eletrodomésticos e Utensílios', 'descricao' => 'Cafeteiras, micro-ondas e itens de copa.'],
            ['nome' => 'Equipamentos de TI', 'descricao' => 'Computadores, impressoras e periféricos de TI.'],
            ['nome' => 'Móveis Diversos', 'descricao' => 'Outros tipos de móveis não classificados anteriormente.'],
            ['nome' => 'Mobiliário Geral', 'descricao' => 'Sofás e outros itens de uso geral.'],
            ['nome' => 'Equipamentos Audiovisuais', 'descricao' => 'Câmeras, caixas de som e aparelhos de áudio.'],
        ]);
    }
}