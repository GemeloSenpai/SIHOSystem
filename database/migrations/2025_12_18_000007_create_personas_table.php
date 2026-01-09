<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id_persona');
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->integer('edad')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('dni', 20)->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};