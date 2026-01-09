<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_paciente';
    protected $table = 'pacientes';

    protected $fillable = [
        'persona_id',
        'codigo_paciente',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paciente) {
            if (empty($paciente->codigo_paciente)) {
                $paciente->codigo_paciente = self::generarCodigoUnico();
            }
        });
    }

    public static function generarCodigoUnico()
    {
        do {
            // Buscar el último código numérico más alto
            $ultimoPaciente = self::where('codigo_paciente', 'LIKE', 'C-%')
                ->orderByRaw('CAST(SUBSTRING(codigo_paciente, 3) AS UNSIGNED) DESC')
                ->first();

            if ($ultimoPaciente && $ultimoPaciente->codigo_paciente) {
                // Extraer el número del código
                $codigoNumero = (int) substr($ultimoPaciente->codigo_paciente, 2);

                // Si llegamos a 9999, continuamos con 10000, 10001, etc.
                if ($codigoNumero >= 9999) {
                    // Buscar el máximo número absoluto
                    $maxNumero = self::where('codigo_paciente', 'LIKE', 'C-%')
                        ->selectRaw('MAX(CAST(SUBSTRING(codigo_paciente, 3) AS UNSIGNED)) as max_num')
                        ->first()
                        ->max_num;

                    $nuevoNumero = $maxNumero + 1;
                } else {
                    $nuevoNumero = $codigoNumero + 1;
                }
            } else {
                $nuevoNumero = 1;
            }

            // Formatear con padding de 4 dígitos (pero puede tener más si > 9999)
            if ($nuevoNumero <= 9999) {
                $codigo = 'C-' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
            } else {
                $codigo = 'C-' . $nuevoNumero; // Sin padding para números > 9999
            }

            // Verificar que no exista (por si acaso)
            $existe = self::where('codigo_paciente', $codigo)->exists();
        } while ($existe);

        return $codigo;
    }

    // Método auxiliar para obtener solo el número del código
    public function getNumeroCodigoAttribute()
    {
        return (int) substr($this->codigo_paciente, 2);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function signosVitales()
    {
        return $this->hasMany(SignosVitales::class, 'paciente_id');
    }

    public function consultas()
    {
        return $this->hasMany(ConsultaDoctor::class, 'paciente_id');
    }

    public function expedientes()
    {
        return $this->hasMany(Expediente::class, 'paciente_id');
    }

    public function relacionConEncargado()
    {
        return $this->hasOne(RelacionPacienteEncargado::class, 'paciente_id', 'id_paciente');
    }

    public function relacionesConEncargado()
    {
        return $this->hasMany(RelacionPacienteEncargado::class, 'paciente_id', 'id_paciente');
    }

    public function relacionEncargado()
    {
        return $this->hasOne(RelacionPacienteEncargado::class, 'id_paciente');
    }

    public function encargado()
    {
        return $this->hasOneThrough(
            Encargado::class,
            RelacionPacienteEncargado::class,
            'paciente_id',    // Foreign key on RelacionPacienteEncargado
            'id_encargado',   // Foreign key on Encargado
            'id_paciente',    // Local key on Paciente
            'encargado_id'    // Local key on RelacionPacienteEncargado
        );
    }
    
    public function relacionPacienteEncargado()
    {
        return $this->hasMany(RelacionPacienteEncargado::class, 'paciente_id')->with('encargado.persona');
    }

    public function visitas()
    {
        return $this->hasMany(RelacionPacienteEncargado::class, 'paciente_id');
    }

    public function relaciones()
    {
        return $this->hasMany(RelacionPacienteEncargado::class, 'paciente_id', 'id_paciente');
    }

    public function ultimaRelacion()
    {
        // requiere Laravel moderno; usa 'fecha_visita' como columna de orden
        return $this->hasOne(RelacionPacienteEncargado::class, 'paciente_id', 'id_paciente')
            ->latestOfMany('fecha_visita');
    }


    public function ultimaRelacionConEncargado()
    {
        return $this->hasOne(RelacionPacienteEncargado::class, 'paciente_id', 'id_paciente')
            ->latest('fecha_visita');
    }
}
