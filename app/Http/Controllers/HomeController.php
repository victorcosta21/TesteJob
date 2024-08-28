<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Clients;
use App\Models\Products;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $clients = Clients::all();
        $products = Products::all();
        
        return view('Body.index', compact('clients', 'products'));
    }

    public function store(Request $request)
    {
        return true;
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
