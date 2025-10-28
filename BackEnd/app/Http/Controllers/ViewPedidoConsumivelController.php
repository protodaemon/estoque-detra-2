<?php

namespace App\Http\Controllers;

use App\Models\ViewPedidoConsumivel;
use Illuminate\Http\Request;

class ViewPedidoConsumivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ViewPedidoConsumivel::query();

        // Filtro por nome (qualquer parte do nome)
        if ($request->filled('nome')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->nome . '%');
            });
        }


        // Filtro por categoria (somente se selecionada)
        if ($request->filled('categoria')) {
            $query->where('categoria_patrimonio', $request->categoria);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $perPage = $request->input('per_page', 12);

        return $query->orderBy('nome')->paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ViewPedidoConsumivel $viewPedidoConsumivel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ViewPedidoConsumivel $viewPedidoConsumivel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ViewPedidoConsumivel $viewPedidoConsumivel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ViewPedidoConsumivel $viewPedidoConsumivel)
    {
        //
    }
}
