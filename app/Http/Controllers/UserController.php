<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
            'edad' => 'required|integer|min:0',
            'fecha_nacimiento' => 'required|date',
            'dni' => 'required|string',
            'sexo' => 'required|in:M,F',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'estado' => 'activo',
        ]);

        Empleado::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'edad' => $request->edad,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'dni' => $request->dni,
            'sexo' => $request->sexo,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
        ]);

        return redirect()->route('admin.usuarios.index')
        ->with('success', 'Nuevo empleado creado correctamente.');
    }
}

