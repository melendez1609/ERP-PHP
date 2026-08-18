<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStatus;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('status')->paginate(12);
        $statuses = ProductStatus::all();

        return view('inventory.index', compact('products', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'              => 'required|string|unique:products,code|max:50',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cost'              => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'min_stock'         => 'required|integer|min:0',
            'product_status_id' => 'required|exists:product_statuses,id',
        ]);

        Product::create($validated);

        return redirect()->back()->with('success', 'Producto creado exitosamente.');
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