<?php

namespace App\Enums;

enum ActitudForasteros: string
{
  case Aislacionista = 'aislacionista';
  case Comerciante = 'comerciante';
  case Hospitalario = 'hospitalario';
  case Neutral = 'neutral';
  case Reservado = 'reservado';
  case Xenófobo = 'xenófobo';

  public function label(): string
  {
    return match ($this) {
      self::Aislacionista => 'Aislacionista',
      self::Comerciante => 'Comerciante',
      self::Hospitalario => 'Hospitalario',
      self::Neutral => 'Neutral',
      self::Reservado => 'Reservado',
      self::Xenófobo => 'Xenófobo',
    };
  }
}
