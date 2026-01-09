<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examen extends Model
{
    use HasFactory;

    protected $table = 'examenes';
    protected $primaryKey = 'id_examen';
    public $timestamps = false;

    protected $fillable = [
        'nombre_examen',
        'categoria_id',
    ];


    public function categoria()
    {
        // IMPORTANTE: si la PK de Categoria es id_categoria, especifica ownerKey
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    /*
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    */
    
    /*
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }
    */

    public function examenesMedicos()
    {
        return $this->hasMany(ExamenMedico::class, 'examen_id', 'id_examen');
    }

    public function examenes()
    {
        return $this->hasMany(\App\Models\Examen::class, 'categoria_id', 'id_categoria');
    }
    

}
