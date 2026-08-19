<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

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

        Supplier::create($validatedData);

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

        return back()->with('success', 'Proveedor actualizado con éxito.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return back()->with('success', 'Proveedor eliminado con éxito.');
    }
}