<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nombres extends Model
{
  use HasFactory;

  protected $table = 'nombres';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
      'lista',
      'tipo',
  ];
}
