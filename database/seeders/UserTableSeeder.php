<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordadmin'),
            'role' => 'admin',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario médico
        User::create([
            'name' => 'Dr. Carlos Mendoza',
            'email' => 'carlos.mendoza@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'medico',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario médico 2
        User::create([
            'name' => 'Dra. Laura Rodríguez',
            'email' => 'laura.rodriguez@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'medico',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario enfermero
        User::create([
            'name' => 'Enf. Sandra Martínez',
            'email' => 'sandra.martinez@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'enfermero',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario enfermero 2
        User::create([
            'name' => 'Enf. Roberto Jiménez',
            'email' => 'roberto.jimenez@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'enfermero',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario recepcionista
        User::create([
            'name' => 'Recepcionista Ana García',
            'email' => 'ana.garcia@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'recepcionista',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario recepcionista 2
        User::create([
            'name' => 'Recepcionista Miguel Torres',
            'email' => 'miguel.torres@hospital.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passwordsiho'),
            'role' => 'recepcionista',
            'estado' => 'activo',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Tabla users poblada exitosamente');
        $this->command->info('👑 Administrador: admin@hospital.com / passwordadmin');
        $this->command->info('👥 Usuarios regulares: [email] / passwordsiho');
    }
}