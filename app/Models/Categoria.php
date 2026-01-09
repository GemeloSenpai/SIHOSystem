<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria',
    ];

    public function examenes()
    {
        return $this->hasMany(Examen::class, 'categoria_id');
    }

    /* 
    public function examenes()
    {
        return $this->hasMany(Examen::class, 'categoria_id', 'id_categoria');
    }
    */
}
