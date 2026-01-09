<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    protected $model = Paciente::class;
    
    // Contador para códigos únicos
    private static $codigoCounter = 1;

    public function definition(): array
    {
        // Generar código único de paciente: PAC-AÑO-SECUENCIA (ej: PAC-2024-0001)
        $year = date('Y');
        $secuencia = str_pad(self::$codigoCounter++, 4, '0', STR_PAD_LEFT);
        $codigo = "PAC-{$year}-{$secuencia}";
        
        return [
            'persona_id' => Persona::factory(),
            'codigo_paciente' => $codigo,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
    
    public function configure(): static
    {
        return $this->afterMaking(function (Paciente $paciente) {
            // Asegurar que la persona asociada tenga un DNI único de 13 dígitos
            if ($paciente->persona && !$paciente->persona->dni) {
                $paciente->persona->dni = $this->generateDNIPaciente();
            }
        });
    }
    
    private function generateDNIPaciente(): string
    {
        // DNI de 13 dígitos para pacientes: 001DDMMYYYYXXXXA
        // 001 = código departamento fijo
        // DDMMYYYY = fecha de nacimiento
        // XXXX = correlativo único
        // A = letra verificadora
        
        $departamento = '001'; // Código fijo
        $fechaNac = $this->faker->date('dmY'); // Formato DDMMYYYY
        $correlativo = str_pad($this->faker->unique()->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT);
        $letra = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F']);
        
        return "{$departamento}{$fechaNac}{$correlativo}{$letra}";
    }
    
    // Estados personalizados opcionales
    public function reciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }
    
    public function antiguo(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 months'),
        ]);
    }
}