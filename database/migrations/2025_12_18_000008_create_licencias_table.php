<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id('id_licencias');
            $table->string('clave', 191)->nullable()->unique();
            $table->text('clave_enc')->nullable();
            $table->char('clave_fp', 64)->nullable()->unique();
            $table->string('clave_hash', 255)->nullable();
            $table->enum('tipo', ['trial', 'pro', 'enterprise', 'demo'])->default('pro');
            $table->enum('estado', ['activa', 'suspendida', 'expirada'])->default('activa');
            $table->string('dominio', 255)->nullable();
            $table->string('hostname', 255)->nullable();
            $table->timestamp('activada_en')->nullable();
            $table->timestamp('expira_en')->nullable();
            $table->timestamps();
            
            $table->index(['estado', 'expira_en'], 'licencias_estado_expira_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
};