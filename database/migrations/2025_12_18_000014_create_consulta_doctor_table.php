<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta_doctor', function (Blueprint $table) {
            $table->id('id_consulta_doctor');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('signos_vitales_id')->constrained('signos_vitales', 'id_signos_vitales')->onDelete('cascade');
            $table->text('resumen_clinico')->nullable();
            $table->text('impresion_diagnostica')->nullable();
            $table->text('indicaciones')->nullable();
            $table->enum('urgencia', ['si', 'no']);
            $table->enum('tipo_urgencia', ['medica', 'pediatrica', 'quirurgico', 'gineco obstetrica'])->nullable();
            $table->enum('resultado', ['alta', 'seguimiento', 'referido']);
            $table->date('citado')->nullable();
            $table->enum('firma_sello', ['si', 'no']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta_doctor');
    }
};