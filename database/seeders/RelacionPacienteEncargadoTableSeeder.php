<?php

namespace Database\Seeders;

use App\Models\RelacionPacienteEncargado;
use App\Models\Paciente;
use App\Models\Encargado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RelacionPacienteEncargadoTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('👥 Creando relaciones paciente-encargado (visitas)...');
        
        $pacientes = Paciente::all();
        $encargados = Encargado::all();
        
        if ($pacientes->isEmpty()) {
            $this->command->warn('❌ No hay pacientes para crear visitas');
            return;
        }
        
        // ================================
        // PASO 1: GARANTIZAR UNA VISITA PARA CADA PACIENTE
        // ================================
        $this->command->info('✅ Garantizando al menos una visita por paciente...');
        
        foreach ($pacientes as $paciente) {
            // Verificar si ya tiene al menos una visita
            $tieneVisitas = RelacionPacienteEncargado::where('paciente_id', $paciente->id_paciente)->exists();
            
            if (!$tieneVisitas) {
                // Crear PRIMERA visita obligatoria
                $conEncargado = rand(0, 100) < 70; // 70% con encargado
                
                RelacionPacienteEncargado::create([
                    'paciente_id' => $paciente->id_paciente,
                    'encargado_id' => $conEncargado && !$encargados->isEmpty() 
                        ? $encargados->random()->id_encargado 
                        : null,
                    'tipo_consulta' => 'general', // Primera visita siempre general
                    'fecha_visita' => now()->subDays(rand(1, 180)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("   👤 Paciente {$paciente->codigo_paciente}: primera visita creada");
            }
        }
        
        // ================================
        // PASO 2: CREAR VISITAS ADICIONALES (algunos pacientes tienen múltiples)
        // ================================
        $this->command->info('📊 Creando visitas adicionales...');
        
        $relacionesAdicionales = 800; // Número de visitas adicionales
        $relacionesCreadas = RelacionPacienteEncargado::count();
        
        for ($i = 0; $i < $relacionesAdicionales; $i++) {
            $paciente = $pacientes->random();
            $conEncargado = rand(0, 100) < 60; // 60% con encargado para visitas adicionales
            
            // Crear visita adicional
            RelacionPacienteEncargado::create([
                'paciente_id' => $paciente->id_paciente,
                'encargado_id' => $conEncargado && !$encargados->isEmpty() 
                    ? $encargados->random()->id_encargado 
                    : null,
                'tipo_consulta' => rand(0, 100) < 30 ? 'especializada' : 'general',
                'fecha_visita' => now()->subDays(rand(0, 90)), // Visitas más recientes
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $relacionesCreadas++;
            
            if ($relacionesCreadas % 200 == 0) {
                $this->command->info("   📈 {$relacionesCreadas} visitas registradas");
            }
        }
        
        // ================================
        // PASO 3: VERIFICACIÓN FINAL
        // ================================
        $this->command->info('🔍 Verificando datos...');
        
        // A. Verificar que TODOS los pacientes tengan al menos una visita
        $pacientesSinVisitas = Paciente::whereDoesntHave('relacionesConEncargado')->count();
        
        if ($pacientesSinVisitas > 0) {
            $this->command->error("❌ Aún hay {$pacientesSinVisitas} pacientes sin visitas");
            
            // Corregir inmediatamente
            foreach (Paciente::whereDoesntHave('relacionesConEncargado')->get() as $paciente) {
                RelacionPacienteEncargado::create([
                    'paciente_id' => $paciente->id_paciente,
                    'encargado_id' => null,
                    'tipo_consulta' => 'general',
                    'fecha_visita' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info("✅ Pacientes sin visitas corregidos");
        }
        
        // B. Verificar que no haya NULLs en campos críticos
        $nullTipo = DB::table('relacion_paciente_encargado')->whereNull('tipo_consulta')->count();
        $nullFecha = DB::table('relacion_paciente_encargado')->whereNull('fecha_visita')->count();
        
        if ($nullTipo > 0 || $nullFecha > 0) {
            $this->command->error("❌ Hay NULLs en campos críticos");
            $this->command->error("   - tipo_consulta NULL: {$nullTipo}");
            $this->command->error("   - fecha_visita NULL: {$nullFecha}");
            
            // Corregir
            if ($nullTipo > 0) {
                DB::table('relacion_paciente_encargado')
                    ->whereNull('tipo_consulta')
                    ->update(['tipo_consulta' => 'general']);
            }
            
            if ($nullFecha > 0) {
                DB::table('relacion_paciente_encargado')
                    ->whereNull('fecha_visita')
                    ->update(['fecha_visita' => now()]);
            }
            
            $this->command->info("✅ NULLs corregidos");
        }
        
        // ================================
        // RESUMEN FINAL
        // ================================
        $totalVisitas = RelacionPacienteEncargado::count();
        $totalPacientes = Paciente::count();
        $pacientesConVisitas = Paciente::whereHas('relacionesConEncargado')->count();
        $visitasConEncargado = RelacionPacienteEncargado::whereNotNull('encargado_id')->count();
        $visitasSinEncargado = RelacionPacienteEncargado::whereNull('encargado_id')->count();
        
        $this->command->info("═══════════════════════════════════════════════════");
        $this->command->info("✅ RELACIONES PACIENTE-ENCARGADO CREADAS");
        $this->command->info("═══════════════════════════════════════════════════");
        $this->command->info("📊 ESTADÍSTICAS:");
        $this->command->info("   • Total visitas: {$totalVisitas}");
        $this->command->info("   • Total pacientes: {$totalPacientes}");
        $this->command->info("   • Pacientes con visitas: {$pacientesConVisitas}/{$totalPacientes}");
        $this->command->info("   • Visitas con encargado: {$visitasConEncargado} ({$visitasConEncargado}/{$totalVisitas})");
        $this->command->info("   • Visitas sin encargado: {$visitasSinEncargado} ({$visitasSinEncargado}/{$totalVisitas})");
        $this->command->info("   • Promedio visitas/paciente: " . round($totalVisitas / $totalPacientes, 2));
        
        // Distribución de tipos de consulta
        $tipos = DB::table('relacion_paciente_encargado')
            ->select('tipo_consulta', DB::raw('COUNT(*) as count'))
            ->groupBy('tipo_consulta')
            ->get();
            
        foreach ($tipos as $tipo) {
            $porcentaje = round(($tipo->count / $totalVisitas) * 100, 1);
            $this->command->info("   • {$tipo->tipo_consulta}: {$tipo->count} ({$porcentaje}%)");
        }
        
        $this->command->info("═══════════════════════════════════════════════════");
    }
}