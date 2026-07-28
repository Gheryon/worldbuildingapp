<?php

namespace App\Enums;

enum ActitudMagia: string
{
  case Integrada = 'integrada';
  case Aceptada = 'aceptada';
  case Tolerada = 'tolerada';
  case Temida = 'temida';
  case Prohibida = 'prohibida';
  case SoloElite = 'solo elite';
  case Desconocida = 'desconocida';

  public function label(): string
  {
    return match ($this) {
      self::Integrada => 'Integrada',
      self::Aceptada => 'Aceptada',
      self::Tolerada => 'Tolerada',
      self::Temida => 'Temida',
      self::Prohibida => 'Prohibida',
      self::SoloElite => 'Solo élite',
      self::Desconocida => 'Desconocida',
    };
  }
}
