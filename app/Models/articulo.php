<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulo extends Model
{
    use HasFactory;

    protected $table = 'articulosgenericos';
    protected $primaryKey = 'id_articulo';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
        'contenido',
        'tipo',
    ];
}
