<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);

        if ($users->isEmpty() && $users->currentPage() > 1) {
            return redirect()->route('users.index', ['page' => $users->currentPage() - 1]);
        }

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'is_active' => true,
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if ((int) $id === (int) auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario en sesión.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function disable($id)
    {
        if ((int) $id === (int) auth()->id() && auth()->user()->is_active) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'No puedes desactivar tu propio usuario en sesión.'
                ], 403);
            }
            return back()->with('error', 'No puedes inhabilitar tu propio usuario en sesión.');
        }

        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $estado = $user->is_active ? 'activado' : 'inactivado';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $user->is_active,
                'message' => "Usuario {$estado} correctamente."
            ]);
        }

        return back()->with('success', "Usuario {$estado} correctamente.");
    }
}