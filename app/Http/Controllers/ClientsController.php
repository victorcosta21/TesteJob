<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $clients = Clients::all();

        return response()->json($clients);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string|max:14',
        ]);

        $client = new Clients();
        $client->name = $request->name;
        $client->document = $request->document;
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Cliente cadastrado com sucesso!'
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
