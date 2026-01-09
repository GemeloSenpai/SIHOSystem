<?php

namespace Database\Factories;

use App\Models\ConsultaDoctor;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\SignosVitales;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultaDoctorFactory extends Factory
{
    protected $model = ConsultaDoctor::class;

    public function definition(): array
    {
        $motivos = [
            'Dolor abdominal', 'Fiebre y malestar general', 'Tos persistente',
            'Dolor de cabeza frecuente', 'Control de presión arterial',
            'Evaluación de diabetes', 'Consulta por gripe', 'Control de rutina',
            'Dolor de espalda', 'Problemas digestivos'
        ];
        
        $diagnosticos = [
            'Gastritis aguda', 'Hipertensión arterial', 'Infección respiratoria alta',
            'Migraña', 'Diabetes mellitus tipo 2', 'Lumbalgia',
            'Reflujo gastroesofágico', 'Ansiedad generalizada', 'Artrosis'
        ];
        
        $indicaciones = [
            'Reposo y líquidos abundantes', 'Tomar medicamento cada 8 horas',
            'Dieta baja en sal', 'Ejercicio moderado', 'Control en 15 días',
            'Acudir a urgencias si empeora', 'Evitar alimentos picantes',
            'Aplicar hielo localmente', 'Seguimiento con especialista'
        ];
        
        return [
            'paciente_id' => Paciente::factory(),
            'doctor_id' => Empleado::factory()->medico(),
            'signos_vitales_id' => SignosVitales::factory(),
            'resumen_clinico' => $this->faker->randomElement($motivos),
            'impresion_diagnostica' => $this->faker->randomElement($diagnosticos),
            'indicaciones' => $this->faker->randomElement($indicaciones),
            'urgencia' => $this->faker->randomElement(['si', 'no']),
            'tipo_urgencia' => $this->faker->optional(0.3)->randomElement(['medica', 'pediatrica', 'quirurgico', 'gineco obstetrica']),
            'resultado' => $this->faker->randomElement(['alta', 'seguimiento', 'referido']),
            'citado' => $this->faker->optional(0.6)->dateTimeBetween('+1 day', '+30 days'),
            'firma_sello' => $this->faker->randomElement(['si', 'no']),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}