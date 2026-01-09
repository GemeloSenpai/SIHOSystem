<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Session;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    // Mostrando la vista del login
    public function create(): View
    {
        return view('auth.login');
    }

    // Guardando la sesion y autenticacion del usuario y redirigiendo a su dashboard.
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Limita a máximo 2 sesiones
        $sesiones = Session::where('user_id', $user->id)->orderBy('last_activity', 'asc')->get();
                
        if ($sesiones->count() >= 2) {
            // Borrar la sesion mas antigua
            Session::where('id', $sesiones->first()->id)->delete();
        }

        // Retorno del dashboard para cada rol, se superponer a los permisos de middleware 
        return match ($user->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'medico' => redirect()->route('dashboard.medico'),
            'enfermero' => redirect()->route('dashboard.recepcion'),
            'recepcionista' => redirect()->route('dashboard.recepcion'),
            'laboratorio' => redirect()->route('dashboard.laboratorio'),
            default => abort(403, 'Rol no autorizado.'),
        };
    }

    // Elimina una sesion
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function cerrarTodas($userId)
    {
        Session::where('user_id', $userId)->delete();
        return response()->json(['success' => true]);
    }
}
