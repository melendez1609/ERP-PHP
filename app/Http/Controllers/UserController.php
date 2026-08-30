<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Events\UserStatusBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required'         => 'La nueva contraseña es obligatoria.',
            'password.min'              => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'        => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual no es correcta. Verifícala e inténtalo nuevamente.'
                ], 422);
            }
            return back()->with('error', 'La contraseña actual no es correcta. Verifícala e inténtalo nuevamente.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente.'
            ]);
        }

        return back()->with('success', 'Contraseña actualizada correctamente.');
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

        $oldRoleId = (int) $user->role_id;

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($oldRoleId !== (int) $request->role_id) {
            broadcast(new UserStatusBroadcast($user->id, 'role_updated'));
        }

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if ((int) $id === (int) auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'No puedes eliminar tu propio usuario en sesión.'
                ], 403);
            }
            return back()->with('error', 'No puedes eliminar tu propio usuario en sesión.');
        }

        $user = User::findOrFail($id);

        try {
            $user->delete();

            broadcast(new UserStatusBroadcast($user->id, 'logout'));

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente.'
                ]);
            }

            return back()->with('success', 'Usuario eliminado correctamente.');

        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $mensajeError = 'El usuario no puede ser eliminado porque se encuentra asignado a registros en el sistema. En su lugar, utilice la opción de desactivar.';

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

        if (!$user->is_active) {
            broadcast(new UserStatusBroadcast($user->id, 'inactive'));
        }

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