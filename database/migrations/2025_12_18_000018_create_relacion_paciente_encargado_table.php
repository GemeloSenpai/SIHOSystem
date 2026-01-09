<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relacion_paciente_encargado', function (Blueprint $table) {
            $table->id('id_relacion');
            $table->foreignId('paciente_id')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('encargado_id')->nullable()->constrained('encargados', 'id_encargado')->onDelete('cascade');
            $table->enum('tipo_consulta', ['general', 'especializada'])->default('general');
            $table->timestamp('fecha_visita')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relacion_paciente_encargado');
    }
};