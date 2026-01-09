<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SignosVitales extends Model
{
    use HasFactory;

    protected $table = 'signos_vitales';
    protected $primaryKey = 'id_signos_vitales';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'enfermera_id',
        'presion_arterial',
        'fc',
        'fr',
        'temperatura',
        'so2',
        'peso',
        'glucosa',
        'fecha_registro',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    public function enfermera()
    {
        return $this->belongsTo(Empleado::class, 'enfermera_id', 'id_empleado');
    }
}
