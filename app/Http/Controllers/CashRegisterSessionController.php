<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashRegisterSession;
use App\Models\CashRegisterMovement;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CashRegisterSessionController extends Controller
{
    public function open(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'opening_amount' => 'required|numeric|min:0',
            'admin_email'    => 'required|email',
            'admin_password' => 'required|string',
        ]);

        $existingSession = CashRegisterSession::where('status', 'open')->first();
        if ($existingSession) {
            return back()->withErrors(['error' => 'Ya existe una sesión de caja abierta actualmente.']);
        }

        $admin = User::where('email', $request->admin_email)->first();
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return back()->withErrors(['admin_password' => 'Credenciales de administrador inválidas.']);
        }

        CashRegisterSession::create([
            'user_id'        => $request->user_id,
            'admin_id'       => $admin->id,
            'opening_amount' => $request->opening_amount,
            'status'         => 'open',
            'opened_at'      => now(),
        ]);

        return redirect()->route('cash-register.index')->with('success', 'Caja aperturada correctamente.');
    }

    public function close(Request $request)
    {
        $request->validate([
            'closing_amount' => 'required|numeric|min:0',
            'admin_password' => 'required|string',
        ]);

        $session = CashRegisterSession::where('status', 'open')->latest()->first();
        if (!$session) {
            return back()->withErrors(['error' => 'No existe ninguna caja abierta para cerrar.']);
        }

        $admin = User::where('role_id', 1)->get()->filter(function ($user) use ($request) {
            return Hash::check($request->admin_password, $user->password);
        })->first();

        if (!$admin && Auth::check() && Auth::user()->role_id === 1 && Hash::check($request->admin_password, Auth::user()->password)) {
            $admin = Auth::user();
        }

        if (!$admin) {
            return back()->withErrors(['admin_password' => 'Contraseña de administrador incorrecta.']);
        }

        $inMovements = $session->movements()->where('type', 'in')->sum('amount');
        $outMovements = $session->movements()->where('type', 'out')->sum('amount');
        $salesTotal = Sale::where('cash_register_session_id', $session->id)->sum('total');

        $expectedAmount = $session->opening_amount + $inMovements - $outMovements + $salesTotal;
        $difference = $request->closing_amount - $expectedAmount;

        if (abs($difference) > 0.001 && !$request->has('confirm_discrepancy')) {
            $typeText = $difference < 0 ? 'Faltante' : 'Sobrante';
            $diffFormatted = number_format(abs($difference), 2);
            $expectedFormatted = number_format($expectedAmount, 2);
            $closingFormatted = number_format($request->closing_amount, 2);

            return back()->with([
                'cash_discrepancy_warning' => true,
                'closing_amount'           => $request->closing_amount,
                'admin_password'           => $request->admin_password,
                'discrepancy_title'        => "Diferencia de Caja ({$typeText})",
                'discrepancy_message'      => "Se detectó un {$typeText} de \${$diffFormatted}. (Esperado: \${$expectedFormatted} | Contado: \${$closingFormatted}). ¿Desea confirmar el cierre con esta diferencia?"
            ]);
        }

        $session->update([
            'closing_amount' => $request->closing_amount,
            'status'         => 'closed',
            'closed_at'      => now(),
        ]);

        $statusMsg = "Monto Esperado: $" . number_format($expectedAmount, 2) . " | Monto Contado: $" . number_format($request->closing_amount, 2) . ". ";

        if ($difference < 0) {
            $title = "Cierre de Caja - Faltante Registrado";
            $statusMsg .= "Faltante registrado: $" . number_format(abs($difference), 2);
        } elseif ($difference > 0) {
            $title = "Cierre de Caja - Sobrante Registrado";
            $statusMsg .= "Sobrante registrado: $" . number_format($difference, 2);
        } else {
            $title = "Cierre de Caja - Cuadre Perfecto";
            $statusMsg .= "Cuadre perfecto.";
        }

        return redirect()->route('dashboard')->with([
            'cash_closed_message' => $statusMsg,
            'cash_closed_title'   => $title,
        ]);
    }

    public function movement(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:in,out',
            'amount'         => 'required|numeric|min:0.01',
            'description'    => 'required|string|max:255',
            'admin_password' => 'required|string',
        ]);

        $session = CashRegisterSession::where('status', 'open')->latest()->first();
        if (!$session) {
            return back()->withErrors(['error' => 'No hay una caja abierta para registrar movimientos.']);
        }

        $admin = User::where('role_id', 1)->get()->filter(function ($user) use ($request) {
            return Hash::check($request->admin_password, $user->password);
        })->first();

        if (!$admin && Auth::check() && Auth::user()->role_id === 1 && Hash::check($request->admin_password, Auth::user()->password)) {
            $admin = Auth::user();
        }

        if (!$admin) {
            return back()->withErrors(['admin_password' => 'Contraseña de administrador incorrecta.']);
        }

        CashRegisterMovement::create([
            'cash_register_session_id' => $session->id,
            'type'                     => $request->type,
            'amount'                   => $request->amount,
            'description'              => $request->description,
            'authorized_by'            => $admin->id,
        ]);

        return back()->with('success', 'Movimiento registrado correctamente.');
    }
}