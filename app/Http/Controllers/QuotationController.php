<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($quotations->isEmpty() && $quotations->currentPage() > 1) {
            return redirect()->route('quotations.index', ['page' => $quotations->currentPage() - 1]);
        }

        return view('quotations.index', compact('quotations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $items = [];
        $total = 0;

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;

            $items[] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ];
        }

        $quotation = Quotation::create([
            'customer_name' => $request->customer_name,
            'total' => $total,
            'pdf_path' => '',
        ]);

        $fileName = 'cotizacion_' . $quotation->id . '_' . time() . '.pdf';
        $relativePath = 'quotations/' . $fileName;

        $logoPath = public_path('images/dvariedad-logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pdf = Pdf::loadView('quotations.partials.quotation-invoice', [
            'quotation' => $quotation,
            'items' => $items,
            'date' => now()->format('Y-m-d'),
            'logoBase64' => $logoBase64
        ]);

        Storage::disk('local')->put($relativePath, $pdf->output());

        $quotation->update(['pdf_path' => $relativePath]);

        return $pdf->stream("cotizacion_{$quotation->id}.pdf");
    }

    public function download($id)
    {
        $quotation = Quotation::findOrFail($id);

        if (!Storage::disk('local')->exists($quotation->pdf_path)) {
            abort(404, 'El archivo de la cotización no existe.');
        }

        return Storage::disk('local')->response($quotation->pdf_path, "cotizacion_{$quotation->id}.pdf");
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);

        if (Storage::disk('local')->exists($quotation->pdf_path)) {
            Storage::disk('local')->delete($quotation->pdf_path);
        }

        $quotation->delete();

        return back()->with('success', 'Cotización eliminada con éxito.');
    }
}