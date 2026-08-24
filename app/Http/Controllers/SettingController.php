<?php

namespace App\Http\Controllers;

use App\Models\Vat;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function profits()
    {
        return view('settings.profits');
    }

    public function vat()
    {
        $vats = Vat::all();
        return view('settings.vat', compact('vats'));
    }

    public function updateVat(Request $request, Vat $vat)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        $vat->update([
            'rate' => $request->rate,
        ]);

        return redirect()->back()->with('success', 'Tasa de IVA actualizada con éxito.');
    }
}