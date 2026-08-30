<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($contacts->isEmpty() && $contacts->currentPage() > 1) {
            return redirect()->route('contacts.index', ['page' => $contacts->currentPage() - 1]);
        }

        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_type' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
        ]);

        Contact::create($validatedData);

        return back()->with('success', 'Contacto creado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_type' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($validatedData);

        return back()->with('success', 'Contacto actualizado con éxito.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Contacto eliminado con éxito.');
    }
}