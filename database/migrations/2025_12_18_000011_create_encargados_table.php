<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encargados', function (Blueprint $table) {
            $table->id('id_encargado');
            $table->foreignId('persona_id')->constrained('personas', 'id_persona')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
{
        Schema::dropIfExists('encargados');
    }
};