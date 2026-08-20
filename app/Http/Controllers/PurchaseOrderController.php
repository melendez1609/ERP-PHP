<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'items.product'])->latest()->get();
        $suppliers = Supplier::all();
        $products = Product::with('supplier')->get();

        return view('purchase-order.index', compact('purchaseOrders', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'requested_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost' => 'required|numeric|min:0',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['quantity'] * $item['cost'];
        }

        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $supplier->id,
            'user_id' => auth()->id(),
            'requested_date' => $request->requested_date,
            'total' => $total,
            'status' => 'pending',
            'email_sent' => false,
        ]);

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'cost' => $item['cost'],
                'subtotal' => $item['quantity'] * $item['cost'],
            ]);
        }

        // Generar PDF y almacenar en storage/app/private/purchase-order
        $pdf = Pdf::loadView('purchase-order.partials.purchase-order-invoice', compact('purchaseOrder', 'supplier'));
        $fileName = 'orden_compra_' . $purchaseOrder->id . '.pdf';
        $filePath = 'purchase-order/' . $fileName;
        
        Storage::disk('local')->put($filePath, $pdf->output());

        // Enviar correo electrónico al proveedor
        $emailSent = false;
        if (!empty($supplier->email)) {
            try {
                Mail::send('emails.purchase-order', ['purchaseOrder' => $purchaseOrder, 'supplier' => $supplier], function ($message) use ($supplier, $pdf, $fileName) {
                    $message->to($supplier->email)
                            ->subject('Nueva Orden de Compra #' . $supplier->id)
                            ->attachData($pdf->output(), $fileName, [
                                'mime' => 'application/pdf',
                            ]);
                });
                $emailSent = true;
            } catch (\Exception $e) {
                // Control de excepción de correo
            }
        }

        $purchaseOrder->update(['email_sent' => $emailSent]);

        return redirect()->route('purchase-orders.index')->with('success', 'Orden de compra generada correctamente.');
    }

    public function receive($id)
    {
        $order = PurchaseOrder::with('items.product')->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'La orden ya fue procesada.');
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
        }

        $order->update(['status' => 'received']);

        return back()->with('success', 'Pedido recibido y sumado al inventario exitosamente.');
    }

    public function cancel($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'La orden no se puede cancelar.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Orden de compra cancelada.');
    }
}