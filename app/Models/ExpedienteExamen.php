<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpedienteExamen extends Pivot
{
    protected $table = 'expediente_examen';
    public $timestamps = false;
    public $incrementing = false;            // no hay id autoincremental
    protected $primaryKey = null;            // sin PK simple (se usa par compuesto)

    
    /*
    protected $primaryKey = null;
    public $incrementing = false;
    */

    protected $fillable = [
        'expediente_id',
        'examen_medico_id',
        'fecha_registro',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'id_expediente');
    }

    public function examenMedico()
    {
        return $this->belongsTo(ExamenMedico::class, 'examen_medico_id', 'id_examen_medico');
    }
}
