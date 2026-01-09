<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsultaDoctor;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\SignosVitales;

class ConsultaDoctorTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener médicos (usuarios con rol médico)
        $medicos = Empleado::whereHas('user', function($query) {
            $query->where('role', 'medico');
        })->get();
        
        if ($medicos->isEmpty()) {
            $this->command->error('❌ No hay médicos registrados');
            return;
        }
        
        // Obtener pacientes con sus signos vitales
        $pacientes = Paciente::with('signosVitales')->get();
        
        if ($pacientes->isEmpty()) {
            $this->command->error('❌ No hay pacientes registrados');
            return;
        }
        
        $consultas = [];
        $motivos = [
            'Control de rutina',
            'Dolor abdominal',
            'Fiebre y malestar general',
            'Control de presión arterial',
            'Consulta por tos persistente',
            'Evaluación de resultados de laboratorio',
            'Dolor de cabeza frecuente',
            'Control de diabetes',
            'Revisión post-operatoria',
            'Chequeo general',
        ];
        
        $diagnosticos = [
            'Gastritis',
            'Hipertensión arterial controlada',
            'Infección respiratoria alta',
            'Diabetes mellitus tipo 2',
            'Artrosis',
            'Ansiedad generalizada',
            'Reflujo gastroesofágico',
            'Anemia ferropénica',
            'Hipercolesterolemia',
            'Obesidad',
        ];
        
        $indicaciones = [
            'Omeprazol 20mg diario',
            'Reposo y líquidos abundantes',
            'Dieta baja en sal',
            'Ejercicio regular',
            'Control de glucosa semanal',
            'Seguimiento en 15 días',
            'Tomar medicamento con alimentos',
            'Evitar esfuerzos físicos',
            'Acudir a urgencias si empeora',
            'Regresar para control',
        ];
        
        foreach ($pacientes as $index => $paciente) {
            // Verificar que el paciente tenga signos vitales
            if ($paciente->signosVitales->isEmpty()) {
                $this->command->warn("⚠️  Paciente {$paciente->id_paciente} no tiene signos vitales, saltando...");
                continue;
            }
            
            // Tomar el primer signo vital del paciente
            $signosVitales = $paciente->signosVitales->first();
            
            // Asignar médico alternando entre los disponibles
            $medico = $medicos[$index % $medicos->count()];
            
            // Determinar urgencia (20% de probabilidad)
            $esUrgencia = rand(1, 100) <= 20;
            
            $consultas[] = [
                'paciente_id' => $paciente->id_paciente,
                'doctor_id' => $medico->id_empleado, // ¡CORREGIDO: Usar id_empleado!
                'signos_vitales_id' => $signosVitales->id_signos_vitales,
                'resumen_clinico' => $motivos[$index % count($motivos)],
                'impresion_diagnostica' => $diagnosticos[$index % count($diagnosticos)],
                'indicaciones' => $indicaciones[$index % count($indicaciones)],
                'urgencia' => $esUrgencia ? 'si' : 'no',
                'tipo_urgencia' => $esUrgencia ? $this->getTipoUrgencia() : null,
                'resultado' => $this->getResultado(),
                'citado' => rand(0, 1) ? now()->addDays(rand(7, 30))->format('Y-m-d') : null,
                'firma_sello' => rand(0, 1) ? 'si' : 'no',
                'created_at' => now()->subDays(rand(0, 60)),
                'updated_at' => now(),
            ];
        }
        
        foreach ($consultas as $consulta) {
            ConsultaDoctor::create($consulta);
        }
        
        $this->command->info('✅ Tabla consulta_doctor poblada con ' . count($consultas) . ' registros');
        $this->command->info('👨‍⚕️ Médicos asignados:');
        foreach ($medicos as $medico) {
            $this->command->info("   - ID {$medico->id_empleado}: {$medico->nombre} {$medico->apellido}");
        }
    }
    
    private function getTipoUrgencia(): string
    {
        $tipos = ['medica', 'pediatrica', 'quirurgico', 'gineco obstetrica'];
        return $tipos[array_rand($tipos)];
    }
    
    private function getResultado(): string
    {
        $resultados = ['alta', 'seguimiento', 'referido'];
        return $resultados[array_rand($resultados)];
    }
}