<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('status')->get();
        return view('inventory.index', compact('products'));
    }

    public function store(Request $request)
    {
        // Lógica para guardar desde el modal de creación
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar desde el modal de edición
    }

    public function destroy($id)
    {
        // Lógica para eliminar
    }
}