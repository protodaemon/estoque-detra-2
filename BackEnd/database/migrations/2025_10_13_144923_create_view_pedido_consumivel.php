<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_pedido_consumivel AS
            SELECT
                pc.*
                ,pdc.produtos_consumivel_id
                ,prodc.nome
                ,pdc.quantidade
            FROM pedido_consumivel pc
            INNER JOIN produtos_pedido_consumivel pdc
                ON pdc.pedido_consumivel_id = pc.pedido_consumivel_id
            INNER JOIN produtos_consumivel prodc
                ON prodc.produtos_consumivel_id = pdc.produtos_consumivel_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_pedido_consumivel");
    }
};