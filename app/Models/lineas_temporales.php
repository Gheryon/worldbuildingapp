<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lineas_temporales extends Model
{
    use HasFactory;
    protected $table = 'lineas_temporales';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
    ];
}
