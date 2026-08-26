<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductStatus;
use App\Models\Supplier;
use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['status', 'supplier', 'vat', 'profitMargin', 'batches'])->paginate(10);

        if ($products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('inventory.index', ['page' => $products->currentPage() - 1]);
        }

        $statuses = ProductStatus::all();
        $suppliers = Supplier::all(); 
        $vats = Vat::where('status', true)->get();

        return view('inventory.index', compact('products', 'statuses', 'suppliers', 'vats'));
    }

    public function getBatches(Product $product)
    {
        $batches = $product->batches()
            ->where('quantity_remaining', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get([
                'id', 
                'quantity_received', 
                'quantity_remaining', 
                'cost', 
                'margin_percentage', 
                'price', 
                'created_at'
            ]);

        return response()->json($batches);
    }

    public function getActiveProducts()
    {
        $products = Product::with('supplier')
            ->where('product_status_id', 1)
            ->get(['id', 'code', 'name', 'supplier_id']);

        return response()->json($products);
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
            'vat_id'            => 'required|exists:vats,id',
            'profit_percentage' => 'required|numeric|min:0',
            'product_status_id' => 'required|exists:product_statuses,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create([
                'code'              => $validated['code'],
                'name'              => $validated['name'],
                'description'       => $validated['description'] ?? null,
                'cost'              => $validated['cost'],
                'price'             => $validated['price'],
                'stock'             => $validated['stock'],
                'min_stock'         => $validated['min_stock'],
                'vat_id'            => $validated['vat_id'],
                'product_status_id' => $validated['product_status_id'],
                'supplier_id'       => $validated['supplier_id'] ?? null,
            ]);

            $product->profitMargin()->create([
                'percentage' => $validated['profit_percentage'],
            ]);

            if ($validated['stock'] > 0) {
                ProductBatch::create([
                    'product_id'         => $product->id,
                    'purchase_order_id'  => null,
                    'cost'               => $validated['cost'],
                    'margin_percentage'  => $validated['profit_percentage'],
                    'price'              => $validated['price'],
                    'quantity_received'  => $validated['stock'],
                    'quantity_remaining' => $validated['stock'],
                ]);
            }
        });

        return back()->with('success', 'Producto y lote inicial creados exitosamente.');
    }

    public function addStock(Request $request)
    {
        $validated = $request->validate([
            'product_id'        => 'required|exists:products,id',
            'cost'              => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:1',
            'profit_percentage' => 'required|numeric|min:0',
            'vat_id'            => 'required|exists:vats,id',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);

            ProductBatch::create([
                'product_id'         => $product->id,
                'purchase_order_id'  => null,
                'cost'               => $validated['cost'],
                'margin_percentage'  => $validated['profit_percentage'],
                'price'              => $validated['price'],
                'quantity_received'  => $validated['stock'],
                'quantity_remaining' => $validated['stock'],
            ]);

            $product->increment('stock', $validated['stock']);
            
            $product->update([
                'cost'  => $validated['cost'],
                'price' => $validated['price'],
            ]);

            $product->profitMargin()->updateOrCreate(
                ['product_id' => $product->id],
                ['percentage' => $validated['profit_percentage']]
            );
        });

        return back()->with('success', 'Stock y nuevo lote agregados exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'code'               => 'required|unique:products,code,' . $id,
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'min_stock'          => 'required|integer|min:0',
            'product_status_id'  => 'required|exists:product_statuses,id',
            'supplier_id'        => 'nullable|exists:suppliers,id',
            'price_update_scope' => 'required|in:none,all_batches,specific_batch',
            'batch_id'           => 'required_if:price_update_scope,specific_batch|nullable|exists:product_batches,id',

            'cost'               => 'required_unless:price_update_scope,none|nullable|numeric|min:0',
            'price'              => 'required_unless:price_update_scope,none|nullable|numeric|min:0',
            'vat_id'             => 'required_unless:price_update_scope,none|nullable|exists:vats,id',
            'profit_percentage'  => 'required_unless:price_update_scope,none|nullable|numeric|min:0',

            'stock'              => 'required_if:price_update_scope,specific_batch|nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($product, $validated) {
            $scope = $validated['price_update_scope'];

            if ($scope === 'none') {
                $product->update([
                    'code'              => $validated['code'],
                    'name'              => $validated['name'],
                    'description'       => $validated['description'] ?? null,
                    'min_stock'         => $validated['min_stock'],
                    'product_status_id' => $validated['product_status_id'],
                    'supplier_id'       => $validated['supplier_id'] ?? null,
                ]);
                return;
            }

            if ($scope === 'specific_batch' && !empty($validated['batch_id'])) {
                ProductBatch::where('id', $validated['batch_id'])
                    ->update([
                        'price'              => $validated['price'],
                        'cost'               => $validated['cost'],
                        'margin_percentage'  => $validated['profit_percentage'],
                        'quantity_remaining' => $validated['stock'],
                    ]);

                $newStock = $product->batches()->sum('quantity_remaining');

                $product->update([
                    'code'              => $validated['code'],
                    'name'              => $validated['name'],
                    'description'       => $validated['description'] ?? null,
                    'cost'              => $validated['cost'],
                    'price'             => $validated['price'],
                    'stock'             => $newStock,
                    'min_stock'         => $validated['min_stock'],
                    'vat_id'            => $validated['vat_id'],
                    'product_status_id' => $validated['product_status_id'],
                    'supplier_id'       => $validated['supplier_id'] ?? null,
                ]);

                $product->profitMargin()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['percentage' => $validated['profit_percentage']]
                );

            } elseif ($scope === 'all_batches') {
                ProductBatch::where('product_id', $product->id)
                    ->where('quantity_remaining', '>', 0)
                    ->update([
                        'price'             => $validated['price'],
                        'cost'              => $validated['cost'],
                        'margin_percentage' => $validated['profit_percentage'],
                    ]);

                $product->update([
                    'code'              => $validated['code'],
                    'name'              => $validated['name'],
                    'description'       => $validated['description'] ?? null,
                    'cost'              => $validated['cost'],
                    'price'             => $validated['price'],
                    'min_stock'         => $validated['min_stock'],
                    'vat_id'            => $validated['vat_id'],
                    'product_status_id' => $validated['product_status_id'],
                    'supplier_id'       => $validated['supplier_id'] ?? null,
                ]);

                $product->profitMargin()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['percentage' => $validated['profit_percentage']]
                );
            }
        });

        return back()->with('success', 'Producto e inventario actualizados exitosamente.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Producto eliminado exitosamente.');
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