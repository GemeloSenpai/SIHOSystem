<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /** Roles permitidos */
    private array $roles = ['admin','recepcionista','enfermero','medico','laboratorio'];

    public function index(Request $request)
    {
        $usuarios = Empleado::with('user')
            ->select([
                'id_empleado',
                'user_id',
                'nombre',
                'apellido',
                'edad',
                'fecha_nacimiento',
                'dni',
                'sexo',
                'direccion',
                'telefono',
            ])
            ->orderBy('id_empleado', 'desc')
            ->paginate(25);

        // Agregar email, role y estado desde la relación user
        $usuarios->getCollection()->transform(function ($empleado) {
            $empleado->email = $empleado->user->email ?? '';
            $empleado->role = $empleado->user->role ?? '';
            $empleado->estado = $empleado->user->estado ?? '';
            return $empleado;
        });

        return view('logica.admin.gestionar-usuarios', [
            'usuarios' => $usuarios,
            'roles'    => $this->roles,
        ]);
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if ($q === '') {
            $data = Empleado::with('user')
                ->select([
                    'id_empleado',
                    'user_id',
                    'nombre',
                    'apellido',
                    'edad',
                    'fecha_nacimiento',
                    'dni',
                    'sexo',
                    'direccion',
                    'telefono',
                ])
                ->orderBy('id_empleado', 'desc')
                ->paginate(25);
            
            $data->getCollection()->transform(function ($empleado) {
                return [
                    'id_empleado' => $empleado->id_empleado,
                    'user_id' => $empleado->user_id,
                    'nombre' => $empleado->nombre,
                    'apellido' => $empleado->apellido,
                    'edad' => $empleado->edad,
                    'fecha_nacimiento' => $empleado->fecha_nacimiento,
                    'dni' => $empleado->dni,
                    'sexo' => $empleado->sexo,
                    'direccion' => $empleado->direccion,
                    'telefono' => $empleado->telefono,
                    'email' => $empleado->user->email ?? '',
                    'role' => $empleado->user->role ?? '',
                    'estado' => $empleado->user->estado ?? '',
                ];
            });

            return response()->json(['mode' => 'paginated', 'data' => $data]);
        }

        $like = '%'.str_replace(' ', '%', $q).'%';
        
        $data = Empleado::with('user')
            ->where(function($query) use ($like) {
                $query->where('nombre', 'like', $like)
                      ->orWhere('apellido', 'like', $like)
                      ->orWhere('dni', 'like', $like)
                      ->orWhere('telefono', 'like', $like)
                      ->orWhereHas('user', function($q) use ($like) {
                          $q->where('email', 'like', $like);
                      });
            })
            ->select([
                'id_empleado',
                'user_id',
                'nombre',
                'apellido',
                'edad',
                'fecha_nacimiento',
                'dni',
                'sexo',
                'direccion',
                'telefono',
            ])
            ->orderBy('id_empleado', 'desc')
            ->limit(250)
            ->get()
            ->map(function($empleado) {
                return [
                    'id_empleado' => $empleado->id_empleado,
                    'user_id' => $empleado->user_id,
                    'nombre' => $empleado->nombre,
                    'apellido' => $empleado->apellido,
                    'edad' => $empleado->edad,
                    'fecha_nacimiento' => $empleado->fecha_nacimiento,
                    'dni' => $empleado->dni,
                    'sexo' => $empleado->sexo,
                    'direccion' => $empleado->direccion,
                    'telefono' => $empleado->telefono,
                    'email' => $empleado->user->email ?? '',
                    'role' => $empleado->user->role ?? '',
                    'estado' => $empleado->user->estado ?? '',
                ];
            });

        return response()->json(['mode' => 'search', 'total' => $data->count(), 'data' => $data]);
    }

    public function show($id)
    {
        $empleado = Empleado::with('user')
            ->where('id_empleado', $id)
            ->first();

        abort_if(!$empleado, 404);
        
        $data = [
            'id_empleado' => $empleado->id_empleado,
            'user_id' => $empleado->user_id,
            'nombre' => $empleado->nombre,
            'apellido' => $empleado->apellido,
            'edad' => $empleado->edad,
            'fecha_nacimiento' => $empleado->fecha_nacimiento,
            'dni' => $empleado->dni,
            'sexo' => $empleado->sexo,
            'direccion' => $empleado->direccion,
            'telefono' => $empleado->telefono,
            'email' => $empleado->user->email ?? '',
            'role' => $empleado->user->role ?? '',
            'estado' => $empleado->user->estado ?? '',
        ];

        return response()->json($data);
    }

    public function edit($id)
    {
        return $this->show($id);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);

        $user = User::find($empleado->user_id);
        abort_if(!$user, 404);

        $request->validate([
            'nombre'   => ['required','string','max:100'],
            'apellido' => ['required','string','max:100'],
            'edad'     => ['nullable','integer','between:0,120'],
            'dni'      => ['required','string','max:50'],
            'fecha_nacimiento' => ['required','date'],
            'sexo'     => ['nullable', Rule::in(['M','F'])],
            'direccion'=> ['nullable','string','max:255'],
            'telefono' => ['nullable','string','max:30'],
            'email'    => ['required','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            'role'     => ['required', Rule::in($this->roles)],
            'estado'   => ['required', Rule::in(['activo','inactivo'])],
            'password' => ['nullable','string','min:8','confirmed'],
        ], [], [
            'password' => 'contraseña',
            'fecha_nacimiento' => 'fecha de nacimiento',
        ]);

        DB::transaction(function () use ($request, $empleado, $user) {
            // Actualizar empleado
            $empleado->update([
                'nombre'    => $request->nombre,
                'apellido'  => $request->apellido,
                'edad'      => $request->edad,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'dni'       => $request->dni,
                'sexo'      => $request->sexo,
                'direccion' => $request->direccion,
                'telefono'  => $request->telefono,
            ]);

            // Actualizar usuario
            $userData = [
                'email'  => $request->email,
                'role'   => $request->role,
                'estado' => $request->estado,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
        });

        return response()->json(['ok' => true, 'message' => 'Usuario actualizado']);
    }

    public function quickUpdate(Request $request, $id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);
        
        $user = User::find($empleado->user_id);
        abort_if(!$user, 404);

        $request->validate([
            'telefono' => ['nullable','string','max:30'],
            'email'    => ['nullable','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            'role'     => ['nullable', Rule::in($this->roles)],
            'estado'   => ['nullable', Rule::in(['activo','inactivo'])],
        ]);

        DB::transaction(function () use ($request, $empleado, $user) {
            if ($request->filled('telefono')) {
                $empleado->update(['telefono' => $request->telefono]);
            }

            $userData = [];
            foreach (['email','role','estado'] as $field) {
                if ($request->filled($field)) {
                    $userData[$field] = $request->input($field);
                }
            }
            
            if (!empty($userData)) {
                $user->update($userData);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Actualización rápida aplicada']);
    }

    public function toggleEstado(Request $request, $id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);

        $user = User::find($empleado->user_id);
        abort_if(!$user, 404);

        $nuevo = $user->estado === 'activo' ? 'inactivo' : 'activo';

        $user->update([
            'estado' => $nuevo,
            'remember_token' => null,
        ]);

        if ($nuevo === 'inactivo' && config('session.driver') === 'database') {
            $sessionsTable = config('session.table', 'sessions');
            DB::table($sessionsTable)->where('user_id', (string) $empleado->user_id)->delete();
        }

        return response()->json(['ok' => true, 'estado' => $nuevo]);
    }

    public function resetPassword(Request $request, $id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);

        $user = User::find($empleado->user_id);
        abort_if(!$user, 404);

        $nueva = Str::password(12, true, true, true, false);

        $user->update([
            'password' => Hash::make($nueva),
        ]);

        return response()->json([
            'ok' => true,
            'password' => $nueva,
            'message' => 'Contraseña restablecida',
        ]);
    }

    public function destroy($id)
    {
        $empleado = Empleado::where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);

        $user = User::find($empleado->user_id);
        abort_if(!$user, 404);

        $user->update([
            'estado' => 'inactivo',
            'remember_token' => null,
        ]);

        if (config('session.driver') === 'database') {
            $sessionsTable = config('session.table', 'sessions');
            DB::table($sessionsTable)->where('user_id', (string) $empleado->user_id)->delete();
        }

        return response()->json(['ok' => true, 'message' => 'Usuario desactivado']);
    }
}