<?php

namespace App\Enums;

enum TipoTerritorio: string
{
  case Llanuras = 'llanuras';
  case Montana = 'montaña';
  case Costa = 'costa';
  case Bosque = 'bosque';
  case Desierto = 'desierto';
  case Jungla = 'jungla';
  case Artico = 'ártico';
  case Subterraneo = 'subterráneo';
  case Maritimo = 'marítimo';
  case Nomada = 'nómada';
  case Archipielago = 'archipiélago';
  case Urbano = 'urbano';
  case Generico = 'genérico';
  case Mixto = 'mixto';

  public function label(): string
  {
    return match ($this) {
      self::Llanuras => 'Llanuras',
      self::Montana => 'Montaña',
      self::Costa => 'Costa',
      self::Bosque => 'Bosque',
      self::Desierto => 'Desierto',
      self::Jungla => 'Jungla',
      self::Artico => 'Ártico',
      self::Subterraneo => 'Subterráneo',
      self::Maritimo => 'Marítimo',
      self::Nomada => 'Nómada',
      self::Archipielago => 'Archipiélago',
      self::Urbano => 'Urbano',
      self::Generico => 'Genérico',
      self::Mixto => 'Mixto',
    };
  }
}
