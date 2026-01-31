<?php

namespace App\Enums;

enum TipoTeismo: string
{
  case Monoteismo = 'monoteismo';
  case Politeismo = 'politeismo';
  case Animismo = 'animismo';
  case Panteismo = 'panteismo';
  case Dualismo = 'dualismo';
  case Ateismo = 'ateismo';
  case Indefinido = 'indefinido';

  public function label(): string
  {
    return match ($this) {
      self::Monoteismo => 'Monoteísmo',
      self::Politeismo => 'Politeísmo',
      self::Animismo => 'Animismo',
      self::Panteismo => 'Panteísmo',
      self::Dualismo => 'Dualismo',
      self::Ateismo => 'Ateísmo',
      self::Indefinido => 'Indefinido',
    };
  }
}
