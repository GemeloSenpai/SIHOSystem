<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_persona';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'personas';

    protected $fillable = [
        'nombre', 
        'apellido', 
        'edad', 
        'fecha_nacimiento',
        'dni', 
        'sexo', 
        'direccion', 
        'telefono'
    ];

    // cast para Carbon
    protected $casts = [
    'fecha_nacimiento' => 'date',
    ];

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'persona_id');
    }

    public function encargado()
    {
        return $this->hasOne(Encargado::class, 'persona_id');
    }

}


