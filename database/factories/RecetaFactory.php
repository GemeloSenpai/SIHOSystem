<?php

namespace Database\Factories;

use App\Models\Receta;
use App\Models\Expediente;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecetaFactory extends Factory
{
    protected $model = Receta::class;

    public function definition(): array
    {
        $expediente = Expediente::inRandomOrder()->first() ?? Expediente::factory();
        
        return [
            'expediente_id' => $expediente->id_expediente,
            'paciente_id' => $expediente->paciente_id,
            'doctor_id' => $expediente->doctor_id,
            'creado_por' => $expediente->doctor->user_id ?? User::inRandomOrder()->first()->id ?? 1,
            'fecha_prescripcion' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'diagnostico' => $this->faker->randomElement([
                'Hipertensión arterial esencial',
                'Diabetes mellitus tipo 2',
                'Gastritis aguda',
                'Infección respiratoria alta'
            ]),
            'receta' => $this->generarRecetaTexto(),
            'observaciones' => $this->faker->optional(0.6)->text(80),
            'edad_paciente_en_receta' => $expediente->paciente->persona->edad ?? $this->faker->numberBetween(25, 70),
            'peso_paciente_en_receta' => round($this->faker->randomFloat(1, 50, 95), 1),
            'alergias_conocidas' => $this->faker->optional(0.3)->randomElement(['Penicilina', 'Sulfas', 'Ninguna']),
            'estado' => $this->faker->randomElement(['activa', 'completada']),
            'firma_digital' => $this->faker->optional(0.5)->md5(),
        ];
    }
    
    private function generarRecetaTexto(): string
    {
        $medicamentos = [
            'Omeprazol 20 mg' => '1 tableta cada 24 horas antes del desayuno (30 días)',
            'Losartán 50 mg' => '1 tableta cada 24 horas',
            'Metformina 850 mg' => '1 tableta cada 12 horas con alimentos',
            'Amoxicilina 500 mg' => '1 tableta cada 8 horas (7 días)',
        ];
        
        $receta = "PRESCRIPCIÓN MÉDICA\n\n";
        $receta .= "Medicamentos:\n";
        
        $numMed = rand(1, 3);
        $meds = array_slice($medicamentos, 0, $numMed);
        
        foreach ($meds as $med => $dosis) {
            $receta .= "- {$med}: {$dosis}\n";
        }
        
        $receta .= "\nInstrucciones: " . $this->faker->randomElement([
            'Tomar con alimentos. No suspender tratamiento.',
            'Completar ciclo completo. Reportar efectos adversos.',
            'Seguir al pie de la letra. No automedicarse.'
        ]);
        
        return $receta;
    }
}