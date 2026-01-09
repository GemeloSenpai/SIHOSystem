<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ExamenMedico extends Model
{
    use HasFactory;

    protected $table = 'examenes_medicos';
    protected $primaryKey = 'id_examen_medico';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'consulta_id',
        'examen_id',
        'fecha_asignacion',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    public function doctor()
    {
        return $this->belongsTo(Empleado::class, 'doctor_id', 'id_empleado');
    }

    public function consulta()
    {
        return $this->belongsTo(ConsultaDoctor::class, 'consulta_id', 'id_consulta_doctor');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id', 'id_examen');
    }

    public function expedientes()
    {
        return $this->belongsToMany(Expediente::class, 'expediente_examen', 'examen_medico_id', 'expediente_id');
    }
}
