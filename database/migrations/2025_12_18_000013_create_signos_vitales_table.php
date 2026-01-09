<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signos_vitales', function (Blueprint $table) {
            $table->id('id_signos_vitales');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('enfermera_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->string('presion_arterial', 10)->nullable();
            $table->integer('fc')->nullable();
            $table->integer('fr')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->integer('so2')->nullable();
            $table->decimal('peso', 6, 2)->nullable();
            $table->decimal('glucosa', 6, 2)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signos_vitales');
    }
};