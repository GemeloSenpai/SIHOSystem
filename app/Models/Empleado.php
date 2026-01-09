<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'edad',
        'fecha_nacimiento',
        'dni',
        'sexo',
        'direccion',
        'telefono',
    ];

    /* quitar ssi falla otra cosa
    public function user()
    { 
        return $this->belongsTo(User::class);
    }
    */

    // En App\Models\Empleado.php
    public function user()
    {
        // La columna en la tabla empleados debería ser 'user_id'
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
