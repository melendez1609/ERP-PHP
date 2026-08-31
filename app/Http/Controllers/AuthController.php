<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SystemLog;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Por favor ingrese su correo electrónico.',
            'email.email'       => 'El formato del correo no es válido.',
            'password.required' => 'Por favor ingrese su contraseña.',
        ]);

        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();

            if (!$user->is_active) {
                SystemLog::log('INTENTO_LOGIN_INACTIVO', [
                    'email' => $user->email,
                ]);

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Su cuenta se encuentra desactivada. Contacte al administrador.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $request->session()->forget('is_locked');

            SystemLog::log('INICIO_SESION', [
                'email' => $user->email,
                'role'  => $user->role_id ?? 'N/A',
            ]);

            return redirect()->intended('/dashboard');
        }

        SystemLog::log('LOGIN_FALLIDO', [
            'email' => $request->email,
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            SystemLog::log('CIERRE_SESION', [
                'email' => Auth::user()->email,
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function lock()
    {
        if (Auth::check()) {
            SystemLog::log('BLOQUEO_PANTALLA', [
                'email' => Auth::user()->email,
            ]);
        }

        session(['is_locked' => true]);
        return redirect()->route('lockscreen.show');
    }

    public function showLockscreen()
    {
        if (!session('is_locked', false)) {
            return redirect()->route('dashboard');
        }
        return view('auth.lockscreen');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Por favor ingrese su contraseña.',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            SystemLog::log('DESBLOQUEO_FALLIDO', [
                'email' => Auth::user()->email,
            ]);

            return back()->withErrors([
                'password' => 'La contraseña ingresada es incorrecta.',
            ]);
        }

        session()->forget('is_locked');

        SystemLog::log('DESBLOQUEO_PANTALLA', [
            'email' => Auth::user()->email,
        ]);

        return redirect()->route('dashboard');
    }
}