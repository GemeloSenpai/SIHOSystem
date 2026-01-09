<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expediente_examen', function (Blueprint $table) {
            $table->foreignId('expediente_id')->constrained('expedientes', 'id_expediente')->onDelete('cascade');
            $table->foreignId('examen_medico_id')->constrained('examenes_medicos', 'id_examen_medico')->onDelete('cascade');
            $table->timestamp('fecha_registro')->useCurrent();
            
            $table->primary(['expediente_id', 'examen_medico_id']);

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_examen');
    }
};