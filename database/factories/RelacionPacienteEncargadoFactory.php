<?php

namespace Database\Factories;

use App\Models\RelacionPacienteEncargado;
use App\Models\Paciente;
use App\Models\Encargado;
use Illuminate\Database\Eloquent\Factories\Factory;

class RelacionPacienteEncargadoFactory extends Factory
{
    protected $model = RelacionPacienteEncargado::class;

    public function definition(): array
    {
        // OBTENER paciente (DEBE existir)
        $paciente = Paciente::inRandomOrder()->first();
        
        // Decidir si viene con encargado (70% con encargado, 30% solo)
        $conEncargado = $this->faker->boolean(70);
        
        if ($conEncargado) {
            $encargado = Encargado::inRandomOrder()->first();
            $encargadoId = $encargado ? $encargado->id_encargado : null;
        } else {
            $encargadoId = null; // Paciente viene solo
        }
        
        // TIPO DE CONSULTA - SIEMPRE debe tener valor
        $tipoConsulta = $this->faker->randomElement(['general', 'especializada']);
        
        // FECHA DE VISITA - SIEMPRE debe tener valor
        $fechaVisita = $this->faker->dateTimeBetween('-6 months', 'now');
        
        return [
            'paciente_id' => $paciente->id_paciente,
            'encargado_id' => $encargadoId, // Puede ser NULL (paciente solo)
            'tipo_consulta' => $tipoConsulta, // NUNCA NULL
            'fecha_visita' => $fechaVisita,   // NUNCA NULL
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    public function conEncargado(): static
    {
        return $this->state(fn (array $attributes) => [
            'encargado_id' => Encargado::inRandomOrder()->first()->id_encargado ?? null,
        ]);
    }
    
    public function sinEncargado(): static
    {
        return $this->state(fn (array $attributes) => [
            'encargado_id' => null, // Paciente viene solo
        ]);
    }
    
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_consulta' => 'general',
        ]);
    }
    
    public function especializada(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_consulta' => 'especializada',
        ]);
    }
}