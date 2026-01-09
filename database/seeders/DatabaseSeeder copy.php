<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   /*
        $this->call([
            UserTableSeeder::class,
            EmpleadoTableSeeder::class,
            PersonaTableSeeder::class,
            PacienteTableSeeder::class,
            EncargadoTableSeeder::class,
            RelacionPacienteEncargadoTableSeeder::class,
            SignosVitalesTableSeeder::class,
            ConsultaDoctorTableSeeder::class,
            CategoriaTableSeeder::class,
            ExamenTableSeeder::class,
            ExamenMedicoTableSeeder::class,
            ExpedienteTableSeeder::class,
            ExpedienteExamenTableSeeder::class,
        ]);
        */

       // ORDEN CORRECTO DE DEPENDENCIAS
        $this->call([
            // Tablas base
            UserTableSeeder::class,
            PersonaTableSeeder::class,
            CategoriaTableSeeder::class,
            ExamenTableSeeder::class,
            
            // Dependen de users y personas
            EmpleadoTableSeeder::class,
            PacienteTableSeeder::class,
            EncargadoTableSeeder::class,
            
            // Dependen de pacientes y empleados
            RelacionPacienteEncargadoTableSeeder::class,
            SignosVitalesTableSeeder::class,
            
            // Dependen de lo anterior
            ConsultaDoctorTableSeeder::class,
            
            // Dependen de consultas
            ExpedienteTableSeeder::class,
            
            // Tablas de relación N:M (opcionales)
            // ExameneMedicoTableSeeder::class,
            // ExpedienteExamenTableSeeder::class,
            // RecetaTableSeeder::class,
        ]);

        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ BASE DE DATOS COMPLETAMENTE POBLADA');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('🔑 ACCESO RÁPIDO:');
        $this->command->info('👑 Admin: admin@hospital.com / passwordadmin');
        $this->command->info('👨‍⚕️ Médico 1: carlos.mendoza@hospital.com / passwordsiho');
        $this->command->info('👩‍⚕️ Médico 2: laura.rodriguez@hospital.com / passwordsiho');
        $this->command->info('═══════════════════════════════════════════════════');

        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ BASE DE DATOS POBLADA EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('🔑 CREDENCIALES DE ACCESO:');
        $this->command->info('👑 Administrador: admin@hospital.com / passwordadmin');
        $this->command->info('👥 Empleados: [email] / passwordsiho');
        $this->command->info('═══════════════════════════════════════════════════');

        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ BASE DE DATOS POBLADA EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('📊 RESUMEN DE DATOS CREADOS:');
        $this->command->info('👥 Usuarios: 7 (1 admin + 6 empleados)');
        $this->command->info('👤 Personas: 10 (7 empleados + 3 pacientes)');
        $this->command->info('💼 Empleados: 7 (2 médicos, 2 enfermeros, 2 recepcionistas, 1 admin)');
        $this->command->info('📋 Categorías: 20 tipos de exámenes');
        $this->command->info('🔬 Exámenes: +50 exámenes médicos');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('🔑 CREDENCIALES DE ACCESO:');
        $this->command->info('👑 Administrador: admin@hospital.com / passwordadmin');
        $this->command->info('👨‍⚕️ Médicos: [email] / passwordsiho');
        $this->command->info('👩‍⚕️ Enfermeros: [email] / passwordsiho');
        $this->command->info('👩‍💼 Recepcionistas: [email] / passwordsiho');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('⚠️  RECUERDA CAMBIAR LAS CONTRASEÑAS EN PRODUCCIÓN');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}
