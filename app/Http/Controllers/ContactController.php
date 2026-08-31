<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\SystemLog;

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

        $contact = Contact::create($validatedData);

        SystemLog::log('CONTACTO_CREADO', [
            'contact_id'   => $contact->id,
            'name'         => $contact->name,
            'contact_type' => $contact->contact_type,
            'email'        => $contact->email,
        ]);

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

        SystemLog::log('CONTACTO_ACTUALIZADO', [
            'contact_id'   => $contact->id,
            'name'         => $contact->name,
            'contact_type' => $contact->contact_type,
            'email'        => $contact->email,
        ]);

        return back()->with('success', 'Contacto actualizado con éxito.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contactName = $contact->name;
        $contact->delete();

        SystemLog::log('CONTACTO_ELIMINADO', [
            'contact_id' => $id,
            'name'       => $contactName,
        ]);

        return back()->with('success', 'Contacto eliminado con éxito.');
    }
}