<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->paginate(10);
        return view('purchase-order.index', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products'    => 'required|array|min:1',
        ]);

        $productsData = array_values($request->products);
        $subtotal = 0;

        foreach ($productsData as $item) {
            $subtotal += ($item['cost'] * $item['quantity']);
        }

        $total = $subtotal; 

        $orderNumber = 'OC-' . strtoupper(uniqid());

        $purchaseOrder = PurchaseOrder::create([
            'supplier_id'  => $request->supplier_id,
            'user_id'      => auth()->id(),
            'order_number' => $orderNumber,
            'products'     => $productsData,
            'subtotal'     => $subtotal,
            'total'        => $total,
            'status'       => 'pendiente',
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Orden de Compra creada correctamente.');
    }
}