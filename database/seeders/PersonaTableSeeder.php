<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use Carbon\Carbon;

class PersonaTableSeeder extends Seeder
{
    public function run(): void
    {
        // Personas para empleados
        $personas = [
            // Administrador
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'edad' => 35,
                'fecha_nacimiento' => Carbon::parse('1988-05-15'),
                'dni' => '0370199548559',
                'sexo' => 'M',
                'direccion' => 'Oficina Central, Hospital General',
                'telefono' => '2222-0001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Médico 1
            [
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza',
                'edad' => 42,
                'fecha_nacimiento' => Carbon::parse('1981-03-20'),
                'dni' => '0370199548558',
                'sexo' => 'M',
                'direccion' => 'Colonia Los Médicos, Casa #45',
                'telefono' => '2222-1001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Médico 2
            [
                'nombre' => 'Laura',
                'apellido' => 'Rodríguez',
                'edad' => 38,
                'fecha_nacimiento' => Carbon::parse('1985-07-12'),
                'dni' => '0370199548599',
                'sexo' => 'F',
                'direccion' => 'Residencial El Médico, #203',
                'telefono' => '2222-1002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Enfermera 1
            [
                'nombre' => 'Sandra',
                'apellido' => 'Martínez',
                'edad' => 32,
                'fecha_nacimiento' => Carbon::parse('1991-07-10'),
                'dni' => '0370199548777',
                'sexo' => 'F',
                'direccion' => 'Barrio San José, #78',
                'telefono' => '2222-1003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Enfermero 2
            [
                'nombre' => 'Roberto',
                'apellido' => 'Jiménez',
                'edad' => 29,
                'fecha_nacimiento' => Carbon::parse('1994-11-25'),
                'dni' => '2570199548559',
                'sexo' => 'M',
                'direccion' => 'Colonia La Esperanza, #56',
                'telefono' => '2222-1004',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Recepcionista 1
            [
                'nombre' => 'Ana',
                'apellido' => 'García',
                'edad' => 28,
                'fecha_nacimiento' => Carbon::parse('1995-11-05'),
                'dni' => '0370199548511',
                'sexo' => 'F',
                'direccion' => 'Residencial Las Flores, #12',
                'telefono' => '2222-1005',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Recepcionista 2
            [
                'nombre' => 'Miguel',
                'apellido' => 'Torres',
                'edad' => 26,
                'fecha_nacimiento' => Carbon::parse('1997-02-18'),
                'dni' => '0370199548855',
                'sexo' => 'M',
                'direccion' => 'Urbanización Bella Vista, #34',
                'telefono' => '2222-1006',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Paciente de ejemplo 1
            [
                'nombre' => 'Juan',
                'apellido' => 'Hernández',
                'edad' => 45,
                'fecha_nacimiento' => Carbon::parse('1978-08-30'),
                'dni' => '0370178948855',
                'sexo' => 'M',
                'direccion' => 'Colonia Centro, #123',
                'telefono' => '7777-0001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Paciente de ejemplo 2
            [
                'nombre' => 'María',
                'apellido' => 'López',
                'edad' => 32,
                'fecha_nacimiento' => Carbon::parse('1991-04-22'),
                'dni' => '0010199548855',
                'sexo' => 'F',
                'direccion' => 'Barrio Nuevo, #456',
                'telefono' => '7777-0002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Paciente de ejemplo 3
            [
                'nombre' => 'Pedro',
                'apellido' => 'González',
                'edad' => 60,
                'fecha_nacimiento' => Carbon::parse('1963-12-10'),
                'dni' => '0370199546325',
                'sexo' => 'M',
                'direccion' => 'Residencial San Juan, #789',
                'telefono' => '7777-0003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($personas as $persona) {
            Persona::create($persona);
        }

        $this->command->info('✅ Tabla personas poblada con 10 registros');
    }
}