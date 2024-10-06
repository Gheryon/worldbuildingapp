<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imagen extends Model
{
  use HasFactory;

  protected $table = 'imagenes';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'path',
    'owner',
    'table_owner',
  ];
}
