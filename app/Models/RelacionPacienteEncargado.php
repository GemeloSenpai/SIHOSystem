<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class RelacionPacienteEncargado extends Model
{
    use HasFactory;

    protected $table = 'relacion_paciente_encargado';
    protected $primaryKey = 'id_relacion';
    public $timestamps = false;

    protected $fillable = [
        'paciente_id',
        'encargado_id',
        'tipo_consulta',
        'fecha_visita',
    ];

    protected $dates = [
        'fecha_visita',
        'created_at',
        'updated_at'
    ];

    // AÑADE ESTO para valores por defecto
    protected $attributes = [
        'tipo_consulta' => 'general',
        'fecha_visita' => null,
    ];
    
    protected $casts = [
        'fecha_visita' => 'datetime', // Carbon según APP_TIMEZONE
    ];

    protected $appends = ['fecha_visita_formateada'];

    // AÑADE ESTE MÉTODO PARA GARANTIZAR DATOS
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Si fecha_visita es null, usar ahora
            if (is_null($model->fecha_visita)) {
                $model->fecha_visita = now();
            }
            
            // Si tipo_consulta es null, usar 'general'
            if (is_null($model->tipo_consulta) || $model->tipo_consulta === '') {
                $model->tipo_consulta = 'general';
            }
        });
    }

    public function getFechaVisitaFormateadaAttribute()
    {
        $dt = $this->fecha_visita; // ya es Carbon en zona APP
        return $dt ? $dt->format('Y-m-d H:i') : null;
    }


    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente')->with('persona'); // Importante para mostrar datos completos;
    }

    public function encargado()
    {
        //return $this->belongsTo(Encargado::class, 'encargado_id', 'id_encargado');
        return $this->belongsTo(Encargado::class, 'encargado_id')->with('persona');
        
    }

    public function personaEncargado()
    {
        return $this->hasOneThrough(
            Persona::class,
            Encargado::class,
            'id_encargado', // Foreign key on Encargado table
            'id_persona',   // Foreign key on Persona table
            'encargado_id', // Local key on Relacion table
            'persona_id'    // Local key on Encargado table
        );
    }
}
