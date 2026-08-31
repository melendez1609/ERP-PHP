<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SystemLog;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($suppliers->isEmpty() && $suppliers->currentPage() > 1) {
            return redirect()->route('suppliers.index', ['page' => $suppliers->currentPage() - 1]);
        }

        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
        ]);

        $supplier = Supplier::create($validatedData);

        SystemLog::log('PROVEEDOR_CREADO', [
            'supplier_id'  => $supplier->id,
            'name'         => $supplier->name,
            'contact_name' => $supplier->contact_name,
            'email'        => $supplier->email,
        ]);

        return back()->with('success', 'Proveedor creado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validatedData);

        SystemLog::log('PROVEEDOR_ACTUALIZADO', [
            'supplier_id'  => $supplier->id,
            'name'         => $supplier->name,
            'contact_name' => $supplier->contact_name,
            'email'        => $supplier->email,
        ]);

        return back()->with('success', 'Proveedor actualizado con éxito.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplierName = $supplier->name;
        $supplier->delete();

        SystemLog::log('PROVEEDOR_ELIMINADO', [
            'supplier_id' => $id,
            'name'        => $supplierName,
        ]);

        return back()->with('success', 'Proveedor eliminado con éxito.');
    }
}