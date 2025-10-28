<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuario')->insert([
            'user' => 'admin',
            'senha' => Hash::make('senha123'), // Criptografando a senha
            'nome' => 'Administrador',
            'email_rec' => 'admin@example.com',
        ]);
    }
}
