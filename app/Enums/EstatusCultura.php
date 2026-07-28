<?php

namespace App\Enums;

enum EstatusCultura: string
{
  case Activa = 'activa';
  case Extinta = 'extinta';
  case EnDeclive = 'en declive';
  case Asimilada = 'asimilada';
  case EnTransicion = 'en transicion';

  public function label(): string
  {
    return match ($this) {
      self::Activa => 'Activa',
      self::Extinta => 'Extinta',
      self::EnDeclive => 'En declive',
      self::Asimilada => 'Asimilada',
      self::EnTransicion => 'En transición',
    };
  }
}
