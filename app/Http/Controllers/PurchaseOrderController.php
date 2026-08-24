<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        $purchaseOrder->load('supplier');

        $pdfPath = null;
        $directory = storage_path('app/private/purchase-orders');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $logoPath = public_path('images/dvariedad-logo.png');
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

        $message = $emailSent 
            ? 'Orden de Compra creada, guardada en PDF y enviada por correo correctamente.' 
            : 'Orden de Compra creada correctamente, pero no se pudo enviar el correo.';

        return redirect()->route('purchase-orders.index')
            ->with('success', $message);
    }

    public function sendPurchaseOrderEmail($purchaseOrder, $pdfPath = null)
    {
        $logoPath = public_path('images/dvariedad-logo.png');
        $logoBase64 = '';

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pdfUrl = \Route::has('purchase-orders.pdf') 
            ? route('purchase-orders.pdf', $purchaseOrder->id) 
            : '#';

        $htmlContent = view('purchase-order.partials.purchase-order-email', compact('purchaseOrder', 'pdfUrl', 'logoBase64'))->render();

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

        $logoPath = public_path('images/dvariedad-logo.png');
        $logoBase64 = '';

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

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

        if ($purchaseOrder->status === 'cancelado') {
            return redirect()->back()->with('error', 'La orden ya se encuentra cancelada.');
        }

        $purchaseOrder->update([
            'status' => 'cancelado'
        ]);

        return redirect()->route('purchase-orders.index')
            ->with('success', "La orden de compra {$purchaseOrder->order_number} fue cancelada correctamente.");
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $pdfPath = storage_path("app/private/purchase-orders/Orden_de_Compra_{$purchaseOrder->order_number}.pdf");

        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'La Orden de Compra y su archivo PDF fueron eliminados correctamente.');
    }
}