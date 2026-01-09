<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id('id_receta');
            $table->foreignId('expediente_id')->unique()->constrained('expedientes', 'id_expediente')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('creado_por')->constrained('users', 'id')->onDelete('cascade');
            $table->date('fecha_prescripcion');
            $table->text('diagnostico');
            $table->text('receta');
            $table->text('observaciones')->nullable();
            $table->integer('edad_paciente_en_receta');
            $table->decimal('peso_paciente_en_receta', 6, 2)->nullable();
            $table->text('alergias_conocidas')->nullable();
            $table->enum('estado', ['activa', 'completada', 'suspendida', 'cancelada'])->default('activa');
            $table->string('firma_digital', 255)->nullable();
            $table->timestamps();
            
            $table->index(['expediente_id'], 'idx_receta_expediente');
            $table->index(['paciente_id'], 'idx_receta_paciente');
            $table->index(['doctor_id'], 'idx_receta_doctor');
            $table->index(['fecha_prescripcion'], 'idx_receta_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};