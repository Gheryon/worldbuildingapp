<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    use HasFactory;

    protected $table = 'lugares';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
        'descripcion_breve',
        'otros_nombres',
        'geografia',
        'ecosistema',
        'clima',
        'flora_fauna',
        'recursos',
        'historia',
        'otros',
        'id_owner',
        'id_tipo_lugar',
    ];
}
