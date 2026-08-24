<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStatus;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['status', 'supplier'])->paginate(10);

        if ($products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('inventory.index', ['page' => $products->currentPage() - 1]);
        }

        $statuses = ProductStatus::all();
        $suppliers = Supplier::all(); 
        return view('inventory.index', compact('products', 'statuses', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'              => 'required|unique:products,code',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cost'              => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'min_stock'         => 'required|integer|min:0',
            'product_status_id' => 'required|exists:product_statuses,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
        ]);

        Product::create($validated);
        return back()->with('success', 'Producto creado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'code'              => 'required|unique:products,code,' . $id,
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cost'              => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'min_stock'         => 'required|integer|min:0',
            'product_status_id' => 'required|exists:product_statuses,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
        ]);

        $product->update($validated);
        return back()->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return back()->with('success', 'Producto eliminado exitosamente');
    }

    public function disable($id)
    {
        $product = Product::findOrFail($id);

        $product->product_status_id = ($product->product_status_id == 1) ? 2 : 1;
        $product->save();

        $estado = ($product->product_status_id == 1) ? 'activado' : 'inactivado';

        return back()->with('success', "Producto {$estado} correctamente.");
    }
}