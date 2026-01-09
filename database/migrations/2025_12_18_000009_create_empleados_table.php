<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->integer('edad');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('dni')->unique();
            $table->enum('sexo', ['M', 'F']);
            $table->string('direccion', 255);
            $table->string('telefono', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};