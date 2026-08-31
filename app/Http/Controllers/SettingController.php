<?php

namespace App\Http\Controllers;

use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\SystemLog;

class SettingController extends Controller
{
    public function vat()
    {
        $vats = Vat::all();
        return view('settings.vat', compact('vats'));
    }

    public function storeVat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ], [
            'name.required' => 'El nombre del impuesto es obligatorio.',
            'rate.required' => 'La tasa de impuesto es obligatoria.',
            'rate.numeric'  => 'La tasa debe ser un número válido.',
        ]);

        $vat = Vat::create([
            'name'   => $request->name,
            'rate'   => $request->rate,
            'status' => 1,
        ]);

        SystemLog::log('IMPUESTO_CREADO', [
            'vat_id' => $vat->id,
            'name'   => $vat->name,
            'rate'   => $vat->rate,
        ]);

        return back()->with('success', 'Impuesto creado correctamente.');
    }

    public function destroyVat($id)
    {
        $vat = Vat::findOrFail($id);
        $vatName = $vat->name;
        $vatRate = $vat->rate;

        try {
            $vat->delete();

            SystemLog::log('IMPUESTO_ELIMINADO', [
                'vat_id' => $id,
                'name'   => $vatName,
                'rate'   => $vatRate,
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Impuesto eliminado correctamente.'
                ]);
            }

            return back()->with('success', 'Impuesto eliminado correctamente.');

        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $mensajeError = 'El impuesto no puede ser eliminado porque se encuentra asignado a productos o facturas del sistema.';

                SystemLog::log('ERROR_ELIMINAR_IMPUESTO', [
                    'vat_id' => $id,
                    'name'   => $vatName,
                    'reason' => 'Asignado a productos o facturas existentes',
                ]);

                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $mensajeError
                    ], 422);
                }

                return back()->with('error', $mensajeError);
            }

            throw $e;
        }
    }
}