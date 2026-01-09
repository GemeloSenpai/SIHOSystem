<?php

namespace Database\Factories;

use App\Models\Expediente;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\SignosVitales;
use App\Models\ConsultaDoctor;
use App\Models\Encargado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpedienteFactory extends Factory
{
    protected $model = Expediente::class;
    
    private static $expedienteCounter = 1;

    public function definition(): array
    {
        // Generar código único de expediente: EXP-AÑO-SECUENCIA
        $codigo = 'EXP-' . date('Y') . '-' . str_pad(self::$expedienteCounter++, 4, '0', STR_PAD_LEFT);
        
        $estados = ['abierto', 'cerrado'];
        $motivos = [
            'Consulta general por dolor abdominal',
            'Control de hipertensión arterial',
            'Evaluación de diabetes mellitus',
            'Consulta de emergencia por fiebre',
            'Seguimiento post-operatorio',
            'Chequeo anual de rutina',
            'Evaluación de síntomas respiratorios'
        ];
        
        $diagnosticos = [
            'Hipertensión arterial controlada',
            'Diabetes mellitus compensada',
            'Gastritis crónica',
            'Artrosis degenerativa',
            'Ansiedad generalizada',
            'Obesidad grado I',
            'Asma bronquial leve'
        ];
        
        return [
            'paciente_id' => Paciente::factory(),
            'encargado_id' => Encargado::factory(),
            'enfermera_id' => Empleado::factory()->enfermero(),
            'signos_vitales_id' => SignosVitales::factory(),
            'doctor_id' => Empleado::factory()->medico(),
            'consulta_id' => ConsultaDoctor::factory(),
            'fecha_creacion' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'codigo' => $codigo,
            'estado' => $this->faker->randomElement($estados),
            'motivo_ingreso' => $this->faker->randomElement($motivos),
            'diagnostico' => $this->faker->randomElement($diagnosticos),
            'observaciones' => $this->faker->optional(0.7)->text(100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}