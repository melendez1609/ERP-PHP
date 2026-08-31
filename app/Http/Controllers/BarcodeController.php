<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use App\Models\SystemLog;

class BarcodeController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string',
            'batch_id'     => 'required|integer',
        ]);

        $code = trim($request->product_code);
        $batchId = $request->batch_id;

        $product = Product::where('code', $code)->first();
        if (!$product) {
            return back()->with('error', 'El código de producto ingresado no existe.');
        }

        $batch = ProductBatch::where('product_id', $product->id)
            ->where('id', $batchId)
            ->first();

        if (!$batch) {
            return back()->with('error', "No se encontró el lote con ID '{$batchId}' para el producto {$code}.");
        }

        $skus = ProductSku::where('product_id', $product->id)
            ->where('product_batch_id', $batch->id)
            ->get();

        if ($skus->isEmpty()) {
            return back()->with('warning', "El lote ID '{$batchId}' no tiene códigos de barras (SKUs) asociados.");
        }

        $dns1d = new DNS1D();
        $barcodes = $skus->map(function ($skuItem) use ($dns1d) {
            return [
                'image'  => 'data:image/png;base64,' . $dns1d->getBarcodePNG($skuItem->sku, 'EAN13', 1.1, 30),
                'number' => $skuItem->sku,
            ];
        })->toArray();

        $pdf = Pdf::loadView('barcodes.partials.barcodes-pdf', compact('barcodes'));

        $directoryPath = storage_path('app/private/barcodes');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $fileName = "{$code}_lote_{$batchId}.pdf";
        $fullPath = $directoryPath . DIRECTORY_SEPARATOR . $fileName;

        file_put_contents($fullPath, $pdf->output());

        SystemLog::log('GENERAR_CODIGOS_BARRA', [
            'product_code' => $code,
            'batch_id'     => $batchId,
            'skus_count'   => $skus->count(),
            'filename'     => $fileName,
        ]);

        return $pdf->stream($fileName);
    }

    public function search(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string',
            'batch_id'     => 'required|integer',
        ]);

        $code = trim($request->product_code);
        $batchId = $request->batch_id;

        $fileName = "{$code}_lote_{$batchId}.pdf";
        $filePath = storage_path("app/private/barcodes/{$fileName}");

        if (!file_exists($filePath)) {
            return back()->with('warning', "No existe un archivo PDF guardado para el lote ID '{$batchId}' del producto {$code}.");
        }

        SystemLog::log('CONSULTAR_CODIGOS_BARRA', [
            'product_code' => $code,
            'batch_id'     => $batchId,
            'filename'     => $fileName,
        ]);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
}