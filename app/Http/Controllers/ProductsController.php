<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        return true;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:14',
        ]);

        $client = new Products();
        $client->name = $request->name;
        $client->value = $valor;
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Produto cadastrado com sucesso!'
        ]);
    }

    public function update(Request $request)
    {
        return true;
    }

    public function delete(Request $request)
    {
        return true;
    }
}
