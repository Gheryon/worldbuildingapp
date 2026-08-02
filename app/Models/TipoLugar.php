<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TipoLugar extends Model
{
  use HasFactory;
  protected $table = 'tipo_lugar';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];
}
