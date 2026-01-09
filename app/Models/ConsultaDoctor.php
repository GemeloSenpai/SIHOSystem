<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsultaDoctor extends Model
{
    use HasFactory;

    protected $table = 'consulta_doctor';
    protected $primaryKey = 'id_consulta_doctor';
    public $timestamps = true; // modificado a true para probar las estadisticas

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'signos_vitales_id',
        'resumen_clinico',
        'impresion_diagnostica',
        'indicaciones',
        'urgencia',
        'tipo_urgencia',
        'resultado',
        'citado',
        'firma_sello',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    public function doctor()
    {
        return $this->belongsTo(Empleado::class, 'doctor_id', 'id_empleado');
    }

    public function signosVitales()
    {
        return $this->belongsTo(SignosVitales::class, 'signos_vitales_id', 'id_signos_vitales');
    }

    public function examenesMedicos()
    {
        return $this->hasMany(ExamenMedico::class, 'consulta_id', 'id_consulta_doctor');
    }
}
