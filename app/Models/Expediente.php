<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Expediente extends Model
{
    use HasFactory;

    protected $table = 'expedientes';
    protected $primaryKey = 'id_expediente';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'encargado_id',
        'enfermera_id',
        'signos_vitales_id',
        'doctor_id',
        'consulta_id',
        'fecha_creacion',
        'codigo',
        'estado',
        'motivo_ingreso',
        'diagnostico',
        'observaciones',
    ];

    protected $casts = [
    'fecha_creacion' => 'datetime', // <— si tu columna se llama así
        // o 'created_at' => 'datetime' si usas timestamps por defecto
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id', 'id_encargado');
    }

    public function enfermera()
    {
        return $this->belongsTo(Empleado::class, 'enfermera_id', 'id_empleado');
    }

    public function signosVitales()
    {
        return $this->belongsTo(SignosVitales::class, 'signos_vitales_id', 'id_signos_vitales');
    }

    public function doctor()
    {
        return $this->belongsTo(Empleado::class, 'doctor_id', 'id_empleado');
    }

    public function consulta()
    {
        return $this->belongsTo(ConsultaDoctor::class, 'consulta_id', 'id_consulta_doctor');
    }

    public function examenesMedicos()
    {
        return $this->belongsToMany(ExamenMedico::class, 'expediente_examen', 'expediente_id', 'examen_medico_id');
    }

    // Devuelve el encargado a mostrar (si existe en el expediente, o derivado desde la pivot del paciente)
    public function getEncargadoDerivadoAttribute()
    {
        // 1) Si el expediente ya tiene encargado cargado, úsalo
        if ($this->relationLoaded('encargado') && $this->encargado) return $this->encargado;
        if ($this->encargado) return $this->encargado;

        // 2) Derivar desde la última relación del paciente
        $pac = $this->relationLoaded('paciente') ? $this->paciente : $this->paciente()->first();
        if (!$pac) return null;

        // Necesitamos relaciones + encargado + persona
        if (!$pac->relationLoaded('relacionesConEncargado')) {
            $pac->load(['relacionesConEncargado.encargado.persona']);
        }

        $rel = $pac->relacionesConEncargado->sortByDesc('fecha_visita')->first();
        return optional($rel)->encargado; // trae Encargado con ->persona si se cargó como arriba
    }

    /**
     * Relación con receta (uno a uno)
     */
    public function receta()
    {
        return $this->hasOne(Receta::class, 'expediente_id', 'id_expediente');
    }

    /**
     * Verificar si tiene receta
     */
    public function tieneReceta()
    {
        return $this->receta()->exists();
    }

    /**
     * Obtener receta o crear una vacía para formularios
     */
    public function recetaOCreateEmpty()
    {
        return $this->receta ?? new Receta([
            'expediente_id' => $this->id_expediente,
            'paciente_id' => $this->paciente_id,
            'doctor_id' => $this->doctor_id,
            'fecha_prescripcion' => now(),
            'estado' => 'activa'
        ]);
    }

}
