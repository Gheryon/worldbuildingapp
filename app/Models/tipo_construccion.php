<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_construccion extends Model
{
  use HasFactory;

  protected $table = 'tipo_construccion';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];
}
