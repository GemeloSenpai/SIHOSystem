<?php

namespace Database\Factories;

use App\Models\SignosVitales;
use App\Models\Paciente;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignosVitalesFactory extends Factory
{
    protected $model = SignosVitales::class;

    public function definition(): array
    {
        // Generar presión arterial realista
        $sistolica = $this->faker->numberBetween(90, 180);
        $diastolica = $this->faker->numberBetween(50, 120);
        
        return [
            'paciente_id' => Paciente::factory(),
            'enfermera_id' => Empleado::factory()->enfermero(),
            'presion_arterial' => "{$sistolica}/{$diastolica}",
            'fc' => $this->faker->numberBetween(50, 120),   // Frecuencia cardíaca
            'fr' => $this->faker->numberBetween(10, 25),    // Frecuencia respiratoria
            'temperatura' => round($this->faker->randomFloat(1, 35.5, 39.5), 1),
            'so2' => $this->faker->numberBetween(90, 100),  // Saturación de oxígeno
            'peso' => round($this->faker->randomFloat(1, 40, 120), 1), // kg
            'glucosa' => round($this->faker->randomFloat(1, 70, 250), 1), // mg/dL
            'fecha_registro' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
    
    // Estados personalizados (opcionales)
    public function normotenso(): static
    {
        return $this->state(fn (array $attributes) => [
            'presion_arterial' => $this->faker->randomElement(['110/70', '120/80', '115/75']),
        ]);
    }
    
    public function hipertenso(): static
    {
        return $this->state(fn (array $attributes) => [
            'presion_arterial' => $this->faker->randomElement(['140/90', '150/95', '160/100']),
        ]);
    }
}