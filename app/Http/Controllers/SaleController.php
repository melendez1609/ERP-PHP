<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemLog;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::where('product_status_id', 1)
            ->where('stock', '>', 0)
            ->get(['id', 'code', 'name', 'price', 'stock', 'image']);

        return view('cash-register.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'nullable|numeric|min:0',
        ]);

        $saleId = null;
        $subtotalGeneral = 0;

        try {
            DB::transaction(function () use ($request, &$saleId, &$subtotalGeneral) {
                $subtotalGeneral = 0;
                $itemsData = [];

                foreach ($request->items as $cartItem) {
                    $product = Product::findOrFail($cartItem['product_id']);
                    $quantity = (int) $cartItem['quantity'];

                    if ($product->stock < $quantity) {
                        throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                    }

                    $unitPrice = isset($cartItem['price']) ? (float)$cartItem['price'] : (float)$product->price;
                    $itemSubtotal = $unitPrice * $quantity;
                    $subtotalGeneral += $itemSubtotal;

                    $itemsData[] = [
                        'product_id'   => $product->id,
                        'product_code' => $product->code,
                        'product_name' => $product->name,
                        'cost'         => $product->cost,
                        'price'        => $unitPrice,
                        'quantity'     => $quantity,
                        'subtotal'     => $itemSubtotal,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];

                    $product->decrement('stock', $quantity);

                    $pendingToDeduct = $quantity;
                    $batches = $product->batches()
                        ->where('quantity_remaining', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($pendingToDeduct <= 0) {
                            break;
                        }

                        if ($batch->quantity_remaining <= $pendingToDeduct) {
                            $pendingToDeduct -= $batch->quantity_remaining;
                            $batch->delete();
                        } else {
                            $batch->decrement('quantity_remaining', $pendingToDeduct);
                            $pendingToDeduct = 0;
                        }
                    }
                }

                $sale = Sale::create([
                    'user_id'  => auth()->id(),
                    'subtotal' => $subtotalGeneral,
                    'total'    => $subtotalGeneral,
                ]);

                $saleId = $sale->id;

                foreach ($itemsData as &$item) {
                    $item['sale_id'] = $sale->id;
                }

                SaleItem::insert($itemsData);

                $sale->load('items', 'user');
                $invoiceHtml = view('cash-register.partials.sales-invoice', compact('sale'))->render();
                $fileName = 'invoice-' . str_pad($sale->id, 8, '0', STR_PAD_LEFT) . '.html';
                
                Storage::disk('local')->put('private/invoice/' . $fileName, $invoiceHtml);
            });

            SystemLog::log('VENTA_PROCESADA', [
                'sale_id'     => $saleId,
                'total'       => $subtotalGeneral,
                'items_count' => count($request->items),
            ]);

            return response()->json([
                'success'    => true,
                'message'    => '¡Venta procesada con éxito!',
                'sale_id'    => $saleId,
                'ticket_url' => route('sales.ticket', $saleId)
            ], 201);

        } catch (\Exception $e) {
            SystemLog::log('ERROR_VENTA', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function ticket($id)
    {
        $sale = Sale::with(['items', 'user'])->findOrFail($id);

        SystemLog::log('TICKET_IMPRESO', [
            'sale_id' => $sale->id,
            'total'   => $sale->total,
        ]);

        return view('cash-register.partials.sales-invoice', compact('sale'));
    }

    public function previewTicket(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'nullable|numeric|min:0',
        ]);

        $subtotalGeneral = 0;
        $itemsData = [];

        foreach ($request->items as $cartItem) {
            $product = Product::findOrFail($cartItem['product_id']);
            $quantity = (int) $cartItem['quantity'];
            $unitPrice = isset($cartItem['price']) ? (float)$cartItem['price'] : (float)$product->price;
            $itemSubtotal = $unitPrice * $quantity;
            $subtotalGeneral += $itemSubtotal;

            $itemsData[] = (object) [
                'quantity'     => $quantity,
                'product_name' => $product->name,
                'price'        => $unitPrice,
                'subtotal'     => $itemSubtotal,
            ];
        }

        $sale = (object) [
            'id'         => '00000000',
            'created_at' => now(),
            'user'       => auth()->user(),
            'items'      => collect($itemsData),
            'subtotal'   => $subtotalGeneral,
            'total'      => $subtotalGeneral,
            'is_preview' => true,
        ];

        return view('cash-register.partials.sales-invoice', compact('sale'));
    }
}