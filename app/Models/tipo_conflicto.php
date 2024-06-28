<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_conflicto extends Model
{
    use HasFactory;

    protected $table = 'tipo_conflicto';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
    ];
}
