<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes_medicos', function (Blueprint $table) {
            $table->id('id_examen_medico');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('consulta_id')->constrained('consulta_doctor', 'id_consulta_doctor')->onDelete('cascade');
            $table->foreignId('examen_id')->constrained('examenes', 'id_examen')->onDelete('cascade');
            $table->timestamp('fecha_asignacion')->useCurrent();
            
            $table->unique(['paciente_id', 'consulta_id', 'examen_id'], 'examenes_medicos_paciente_consulta_examen_unique');
        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes_medicos');
    }
};