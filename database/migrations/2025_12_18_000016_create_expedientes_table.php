<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id('id_expediente');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade')->index();
            $table->foreignId('encargado_id')->nullable()->constrained('encargados', 'id_encargado')->onDelete('cascade');
            $table->foreignId('enfermera_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('signos_vitales_id')->constrained('signos_vitales', 'id_signos_vitales')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('consulta_id')->nullable()->constrained('consulta_doctor', 'id_consulta_doctor')->onDelete('cascade');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('codigo', 50)->unique();
            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');
            $table->text('motivo_ingreso')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps(); // Esto crea created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};