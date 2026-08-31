<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\SystemLog;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->paginate(10);
        $suppliers = Supplier::all();

        return view('purchase-order.index', compact('purchaseOrders', 'suppliers'));
    }

    public function activeProducts()
    {
        $products = Product::query()
            ->where('product_status_id', 1)
            ->leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
            ->select('products.id', 'products.code', 'products.name', 'products.cost')
            ->selectRaw('MAX(product_batches.cost) as max_batch_cost')
            ->groupBy('products.id', 'products.code', 'products.name', 'products.cost')
            ->orderBy('products.name')
            ->get()
            ->map(function ($product) {
                $highestCost = max(
                    (float) $product->cost,
                    (float) ($product->max_batch_cost ?? 0)
                );

                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'cost' => number_format($highestCost, 2, '.', ''),
                ];
            });

        return response()->json($products);
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

        $purchaseOrder->load('supplier');

        $pdfPath = null;
        $directory = storage_path('app/private/purchase-orders');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $logoPath = public_path('images/dvariedad-logo-bn.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $fullPath = $directory . '/' . "Orden_de_Compra_{$purchaseOrder->order_number}.pdf";
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase-order.partials.purchase-order-invoice', compact('purchaseOrder', 'logoBase64'));
            $pdf->save($fullPath);
            $pdfPath = $fullPath;
        }

        $emailSent = $this->sendPurchaseOrderEmail($purchaseOrder, $pdfPath);

        SystemLog::log('ORDEN_COMPRA_CREADA', [
            'purchase_order_id' => $purchaseOrder->id,
            'order_number'      => $orderNumber,
            'supplier'          => $purchaseOrder->supplier?->name,
            'total'             => $total,
            'email_sent'        => $emailSent,
        ]);

        $message = $emailSent 
            ? 'Orden de Compra creada, guardada en PDF y enviada por correo correctamente.' 
            : 'Orden de Compra creada correctamente, pero no se pudo enviar el correo.';

        return redirect()->route('purchase-orders.index')
            ->with('success', $message)
            ->with('pdf_url', route('purchase-orders.pdf', $purchaseOrder->id));
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden editar órdenes en estado pendiente.');
        }

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

        DB::transaction(function () use ($purchaseOrder, $request, $productsData, $subtotal, $total) {
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'products'    => $productsData,
                'subtotal'    => $subtotal,
                'total'       => $total,
            ]);

            $purchaseOrder->load('supplier');

            $directory = storage_path('app/private/purchase-orders');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $logoPath = public_path('images/dvariedad-logo-bn.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdfPath = $directory . '/' . "Orden_de_Compra_{$purchaseOrder->order_number}.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase-order.partials.purchase-order-invoice', compact('purchaseOrder', 'logoBase64'));
                $pdf->save($pdfPath);
            }
        });

        SystemLog::log('ORDEN_COMPRA_ACTUALIZADA', [
            'purchase_order_id' => $purchaseOrder->id,
            'order_number'      => $purchaseOrder->order_number,
            'supplier'          => $purchaseOrder->supplier?->name,
            'total'             => $total,
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Orden de Compra actualizada y PDF regenerado correctamente.');
    }

    public function receive($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if (strtolower($purchaseOrder->status) !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden recibir órdenes que estén en estado pendiente.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $productsList = is_array($purchaseOrder->products) 
                ? $purchaseOrder->products 
                : json_decode($purchaseOrder->products, true);

            foreach ($productsList as $item) {
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                $product = Product::findOrFail($productId);
                
                $cost = (float) $item['cost'];
                $quantity = (int) $item['quantity'];

                $profitMargin = $product->profitMargin;
                $marginPercentage = $profitMargin ? $profitMargin->percentage : 0;

                $vatRate = $product->vat?->rate ?? 13.00;
                $priceWithoutVat = $cost * (1 + ($marginPercentage / 100));
                $price = round($priceWithoutVat * (1 + ($vatRate / 100)), 2);

                $batch = ProductBatch::create([
                    'product_id'        => $product->id,
                    'purchase_order_id' => $purchaseOrder->id,
                    'cost'              => $cost,
                    'margin_percentage' => $marginPercentage,
                    'price'             => $price,
                    'quantity_received' => $quantity,
                    'quantity_remaining'=> $quantity,
                ]);

                $this->generateSkusForBatch($product->id, $batch->id, $quantity);

                $product->increment('stock', $quantity);
                $product->update([
                    'cost'  => $cost,
                    'price' => $price,
                ]);

                $product->profitMargin()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['percentage' => $marginPercentage]
                );
            }

            $purchaseOrder->update(['status' => 'recibido']);
        });

        SystemLog::log('ORDEN_COMPRA_RECIBIDA', [
            'purchase_order_id' => $purchaseOrder->id,
            'order_number'      => $purchaseOrder->order_number,
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Orden de Compra recibida exitosamente. Stock, lotes e identificadores SKU agregados al inventario.');
    }

    public function sendPurchaseOrderEmail($purchaseOrder, $pdfPath = null)
    {
        $pdfUrl = \Route::has('purchase-orders.pdf') 
            ? route('purchase-orders.pdf', $purchaseOrder->id) 
            : '#';

        $htmlContent = view('purchase-order.partials.purchase-order-email', compact('purchaseOrder', 'pdfUrl'))->render();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = env('MAIL_PORT', 587);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            
            $supplierEmail = $purchaseOrder->supplier?->email ?? 'proveedor@ejemplo.com';
            $mail->addAddress($supplierEmail, $purchaseOrder->supplier?->name);

            $emailLogoPath = public_path('Images/dvariedad-logo-cl.png');
            if (!file_exists($emailLogoPath)) {
                $emailLogoPath = public_path('images/dvariedad-logo-cl.png');
            }
            if (!file_exists($emailLogoPath)) {
                $emailLogoPath = public_path('images/dvariedad-logo-bn.png');
            }

            if (file_exists($emailLogoPath)) {
                $mail->addEmbeddedImage($emailLogoPath, 'company_logo', 'logo.png');
            }

            if ($pdfPath && file_exists($pdfPath)) {
                $mail->addAttachment($pdfPath, "Orden_de_Compra_{$purchaseOrder->order_number}.pdf");
            }

            $mail->isHTML(true);
            $mail->Subject = "Orden de Compra #" . ($purchaseOrder->order_number ?? $purchaseOrder->id);
            $mail->Body    = $htmlContent;

            $mail->send();
            return true;
        } catch (Exception $e) {
            logger("Error al enviar correo con PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function downloadPdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user'])->findOrFail($id);

        $logoPath = public_path('images/dvariedad-logo-bn.png');
        $logoBase64 = '';

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        SystemLog::log('ORDEN_COMPRA_DESCARGAR_PDF', [
            'purchase_order_id' => $purchaseOrder->id,
            'order_number'      => $purchaseOrder->order_number,
        ]);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase-order.partials.purchase-order-invoice', compact('purchaseOrder', 'logoBase64'));
            return $pdf->download("Orden_de_Compra_{$purchaseOrder->order_number}.pdf");
        }

        return view('purchase-order.partials.purchase-order-invoice', compact('purchaseOrder', 'logoBase64'));
    }

    public function streamPdf($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $pdfPath = storage_path("app/private/purchase-orders/Orden_de_Compra_{$purchaseOrder->order_number}.pdf");

        if (!file_exists($pdfPath)) {
            return back()->with('error', 'El archivo PDF no se encuentra disponible.');
        }

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Orden_de_Compra_' . $purchaseOrder->order_number . '.pdf"'
        ]);
    }

    public function cancel($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $purchaseOrder->update([
            'status' => 'cancelado'
        ]);

        SystemLog::log('ORDEN_COMPRA_CANCELADA', [
            'purchase_order_id' => $purchaseOrder->id,
            'order_number'      => $purchaseOrder->order_number,
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', "La orden de compra {$purchaseOrder->order_number} fue cancelada correctamente.");
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $orderNumber = $purchaseOrder->order_number;

        $pdfPath = storage_path("app/private/purchase-orders/Orden_de_Compra_{$purchaseOrder->order_number}.pdf");

        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        $purchaseOrder->delete();

        SystemLog::log('ORDEN_COMPRA_ELIMINADA', [
            'purchase_order_id' => $id,
            'order_number'      => $orderNumber,
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'La Orden de Compra y su archivo PDF fueron eliminados correctamente.');
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