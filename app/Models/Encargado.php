<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encargado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_encargado';
    protected $table = 'encargados';

    protected $fillable = [
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function expedientes()
    {
        return $this->hasMany(Expediente::class, 'encargado_id');
    }

    public function relacionPacienteEncargado()
    {
        return $this->hasMany(RelacionPacienteEncargado::class, 'encargado_id', 'id_encargado');
    }
}
