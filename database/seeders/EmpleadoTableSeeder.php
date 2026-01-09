<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Empleado;

class EmpleadoTableSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo de emails de usuarios a DNIs de personas
        $asignaciones = [
            'admin@hospital.com' => '0703188952635',
            'carlos.mendoza@hospital.com' => '0703199302559',
            'laura.rodriguez@hospital.com' => '0703199302564',
            'sandra.martinez@hospital.com' => '0703199302554',
            'roberto.jimenez@hospital.com' => '0703199302544',
            'ana.garcia@hospital.com' => '0703199302354',
            'miguel.torres@hospital.com' => '0703197302554',
        ];

        foreach ($asignaciones as $email => $dni) {
            $user = User::where('email', $email)->first();
            $persona = Persona::where('dni', $dni)->first();

            if ($user && $persona) {
                // Verificar si el empleado ya existe
                $empleadoExistente = Empleado::where('user_id', $user->id)->first();
                
                if (!$empleadoExistente) {
                    Empleado::create([
                        'user_id' => $user->id,
                        'nombre' => $persona->nombre,
                        'apellido' => $persona->apellido,
                        'edad' => $persona->edad,
                        'fecha_nacimiento' => $persona->fecha_nacimiento,
                        'dni' => $persona->dni,
                        'sexo' => $persona->sexo,
                        'direccion' => $persona->direccion,
                        'telefono' => $persona->telefono,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("✅ Empleado creado: {$persona->nombre} {$persona->apellido}");
                } else {
                    $this->command->info("⚠️  Empleado ya existe: {$persona->nombre} {$persona->apellido}");
                }
            } else {
                $this->command->error("❌ No se encontró usuario o persona para: {$email}");
            }
        }

        $this->command->info('✅ Tabla empleados poblada exitosamente');
        $this->command->info('📊 Total empleados: ' . Empleado::count());
    }
}