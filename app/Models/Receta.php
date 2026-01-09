<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    
    protected $fillable = [
        'expediente_id',
        'paciente_id',
        'doctor_id',
        'fecha_prescripcion',
        'diagnostico',
        'receta',
        'observaciones',
        'edad_paciente_en_receta',
        'peso_paciente_en_receta',
        'alergias_conocidas',
        'estado',
        'firma_digital',
        'creado_por'
    ];
    
    protected $casts = [
        'fecha_prescripcion' => 'date',
        'edad_paciente_en_receta' => 'integer',
        'peso_paciente_en_receta' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Relación con el expediente
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'id_expediente');
    }
    
    /**
     * Relación con el paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }
    
    /**
     * Relación con el doctor que prescribe
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'doctor_id', 'id_empleado');
    }
    
    /**
     * Relación con el usuario que creó el registro
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por', 'id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por', 'id');
    }

    
    /**
     * Accesor para nombre completo del paciente
     */
    public function getPacienteNombreAttribute()
    {
        return optional($this->expediente->paciente->persona)->nombre_completo ?? 'N/A';
    }
    
    /**
     * Accesor para nombre completo del doctor
     */
    public function getDoctorNombreAttribute()
    {
        return optional($this->doctor)->nombre_completo ?? 'N/A';
    }
    
    /**
     * Accesor para formato de fecha
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_prescripcion->format('d/m/Y');
    }
    
    /**
     * Accesor para estado con color
     */
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'activa' => 'success',
            'completada' => 'info',
            'suspendida' => 'warning',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }
    
    /**
     * Scope para recetas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }
    
    /**
     * Scope para recetas de un doctor específico
     */
    public function scopePorDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
    
    /**
     * Scope para recetas de un paciente específico
     */
    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }
    
    /**
     * Verificar si el usuario actual puede editar esta receta
     */
    public function puedeEditar($userId, $userRole, $empleadoId = null)
    {
        if ($userRole === 'admin') {
            return true;
        }
        
        if ($userRole === 'medico' && $this->doctor_id == $empleadoId) {
            return true;
        }
        
        return false;
    }
}