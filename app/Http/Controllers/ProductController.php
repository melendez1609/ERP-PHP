<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductSku;
use App\Models\ProductStatus;
use App\Models\Supplier;
use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function showImage($filename)
    {
        $path = 'products/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
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
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cost'              => 'required|numeric|min:0',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'min_stock'         => 'required|integer|min:0',
            'vat_id'            => 'required|exists:vats,id',
            'profit_percentage' => 'required|numeric|min:0',
            'product_status_id' => 'required|exists:product_statuses,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
        ]);

        DB::transaction(function () use ($request, $validated) {
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

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = 'product-' . $product->id . '-IMG.' . $extension;
                $imagePath = $file->storeAs('products', $imageName, 'public');

                $product->update(['image' => $imagePath]);
            }

            $product->profitMargin()->create([
                'percentage' => $validated['profit_percentage'],
            ]);

            if ($validated['stock'] > 0) {
                $batch = ProductBatch::create([
                    'product_id'        => $product->id,
                    'purchase_order_id' => null,
                    'cost'              => $validated['cost'],
                    'margin_percentage' => $validated['profit_percentage'],
                    'price'             => $validated['price'],
                    'quantity_received' => $validated['stock'],
                    'quantity_remaining'=> $validated['stock'],
                ]);

                $this->generateSkusForBatch($product->id, $batch->id, $validated['stock']);
            }
        });

        return back()->with('success', 'Producto, lote e identificadores SKU creados exitosamente.');
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

            $batch = ProductBatch::create([
                'product_id'        => $product->id,
                'purchase_order_id' => null,
                'cost'              => $validated['cost'],
                'margin_percentage' => $validated['profit_percentage'],
                'price'             => $validated['price'],
                'quantity_received' => $validated['stock'],
                'quantity_remaining'=> $validated['stock'],
            ]);

            $this->generateSkusForBatch($product->id, $batch->id, $validated['stock']);

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

        return back()->with('success', 'Stock, nuevo lote y SKUs agregados exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'code'               => 'required|unique:products,code,' . $id,
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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

        DB::transaction(function () use ($request, $product, $validated, $id) {
            $scope = $validated['price_update_scope'];
            $imagePath = $product->image;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = 'product-' . $id . '-IMG.' . $extension;
                $imagePath = $file->storeAs('products', $imageName, 'public');
            }

            $updateData = [
                'code'              => $validated['code'],
                'name'              => $validated['name'],
                'description'       => $validated['description'] ?? null,
                'image'             => $imagePath,
                'min_stock'         => $validated['min_stock'],
                'product_status_id' => $validated['product_status_id'],
                'supplier_id'       => $validated['supplier_id'] ?? null,
            ];

            if ($scope === 'none') {
                $product->update($updateData);
                return;
            }

            if ($scope === 'specific_batch' && !empty($validated['batch_id'])) {
                ProductBatch::where('id', $validated['batch_id'])
                    ->update([
                        'price'             => $validated['price'],
                        'cost'              => $validated['cost'],
                        'margin_percentage' => $validated['profit_percentage'],
                        'quantity_remaining'=> $validated['stock'],
                    ]);

                $newStock = $product->batches()->sum('quantity_remaining');

                $updateData['cost']   = $validated['cost'];
                $updateData['price']  = $validated['price'];
                $updateData['stock']  = $newStock;
                $updateData['vat_id'] = $validated['vat_id'];

                $product->update($updateData);

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

                $updateData['cost']   = $validated['cost'];
                $updateData['price']  = $validated['price'];
                $updateData['vat_id'] = $validated['vat_id'];

                $product->update($updateData);

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

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

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

    private function generateSkusForBatch(int $productId, int $batchId, int $quantity): void
    {
        $skus = [];
        $lastId = ProductSku::max('id') ?? 0;
        $now = now();

        for ($i = 1; $i <= $quantity; $i++) {
            $nextId = $lastId + $i;
            $number = '2' . str_pad($nextId, 11, '0', STR_PAD_LEFT);

            $sum = 0;
            for ($j = 0; $j < 12; $j++) {
                $digit = (int) $number[$j];
                $sum += ($j % 2 === 0) ? $digit : $digit * 3;
            }
            $checksum = (10 - ($sum % 10)) % 10;

            $skus[] = [
                'product_id'       => $productId,
                'product_batch_id' => $batchId,
                'sku'              => $number . $checksum,
                'status'           => 'available',
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        ProductSku::insert($skus);
    }
}