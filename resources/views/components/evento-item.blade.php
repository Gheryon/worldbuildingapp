@props(['evento'])

@php
// Mapeo de categorías a grosor de borde en píxeles
$anchosBorde = [
'local' => '1px',
'regional' => '3px',
'continental' => '5px',
'global' => '7px',
'universal' => '12px',
];

$grosor = $anchosBorde[$evento->categoria] ?? '1px';

//Configuración de estilos e iconos según el tipo
$config = match($evento->tipo) {
'nace_personaje', 'nacimiento', 'personajes' => [
'color' => 'success',
'icon' => 'fa-user-tag',
'label' => 'Nacimiento de'
],
'muere_personaje', 'defuncion', 'personajes' => [
'color' => 'danger',
'icon' => 'fa-user-tag',
'label' => 'Muerte de'
],
'ini_conflicto', 'fin_conflicto', 'conflicto', 'conflictos' => [
'color' => 'danger',
'icon' => 'fa-solid fa-shield-halved',
'label' => str_contains($evento->tipo, 'ini') ? 'Comienzo de' : 'Fin de'
],
'ini_asentamiento', 'fin_asentamiento', 'asentamiento', 'asentamientos' => [
'color' => 'info',
'icon' => 'fa-monument',
'label' => str_contains($evento->tipo, 'ini') ? 'Fundación de' : 'Destrucción de'
],
'ini_organizacion', 'fin_organizacion', 'organizacion', 'organizaciones' => [
'color' => 'primary',
'icon' => 'fa-sitemap',
'label' => str_contains($evento->tipo, 'ini') ? 'Fundación de' : 'Disolución de'
],
'fecha_actual' => ['color' => 'dark', 'icon' => 'fa-clock', 'label' => 'Hoy:'],
'crisis' => ['color' => 'warning', 'icon' => 'fa-exclamation-triangle', 'label' => 'Crisis:'],
'logro' => ['color' => 'primary', 'icon' => 'fa-trophy', 'label' => 'Hito:'],
'epidemia' => ['color' => 'warning', 'icon' => 'fa-biohazard', 'label' => 'Brote:'],
'politico' => ['color' => 'dark', 'icon' => 'fa-monument', 'label' => ''],
'religion' => ['color' => 'dark', 'icon' => 'fa-solid fa-place-of-worship', 'label' => ''],
default => ['color' => 'blue', 'icon' => 'fa-calendar-day', 'label' => ''],
};

//Lógica de enrutamiento dinámico
// Mapeamos el tipo de evento a su ruta correspondiente en Laravel
$route = match(true) {
in_array($evento->tipo, ['nace_personaje', 'muere_personaje', 'personajes', 'nacimiento', 'defuncion'])
=> route('personajes.show', $evento->id),

in_array($evento->tipo, ['ini_conflicto', 'fin_conflicto', 'conflicto', 'conflictos'])
=> route('conflicto.show', $evento->id),

in_array($evento->tipo, ['ini_asentamiento', 'fin_asentamiento', 'asentamiento', 'asentamientos'])
=> route('asentamiento.show', $evento->id),

in_array($evento->tipo, ['ini_organizacion', 'fin_organizacion', 'organizacion', 'organizaciones'])
=> route('organizaciones.show', $evento->id),

default => null,
};
@endphp

<div>
  <i class="fas {{ $config['icon'] }} bg-{{ $config['color'] }}"></i>
  <div class="border border-{{$config['color']}} timeline-item shadow-sm" style="border-left-width: {{ $grosor }} !important; border-left-style: solid;">
    <span class="time">
      <i class="fas fa-layer-group"></i> <b class="{{ in_array($evento->categoria, ['global', 'universal']) ? 'text-danger' : '' }}">
    {{ ucfirst($evento->categoria ?? 'Local') }}
  </b>
    </span>

    <h3 class="timeline-header">
      @if($config['label'])
      <span class="text-muted font-weight-light mr-1">{{ $config['label'] }}</span>
      @endif

      {{-- Enlace dinámico si existe ruta y el ID es válido --}}
      @if($route && $evento->id != 0)
      <a href="{{ $route }}" class="font-weight-bold">
        {{ $evento->nombre }}
      </a>
      @else
      <span class="font-weight-bold">{{ $evento->nombre }}</span>
      @endif
    </h3>

    @if($evento->descripcion)
    <div class="timeline-body">
      {{-- Limpieza con HTMLPurifier para seguridad contra XSS --}}
      {!! clean($evento->descripcion) !!}
    </div>
    @endif

    {{-- Acciones solo para eventos editables (tabla 'eventos') --}}
    @if (in_array($evento->tipo, ['general', 'crisis', 'logro', 'epidemia', 'politico', 'religioso']))
    <div class="timeline-footer">
      <button data-id="{{$evento->id}}" type="button" class="editar btn btn-xs btn-outline-primary shadow-sm" data-toggle="modal" data-target="#nuevo-evento">
        <i class="fas fa-edit"></i> Editar
      </button>
      <button data-id="{{$evento->id}}" data-nombre="{{$evento->nombre}}" class="borrar btn btn-xs btn-outline-danger shadow-sm" data-toggle="modal" data-target="#eliminar-evento">
        <i class="fas fa-trash-alt"></i>
      </button>
    </div>
    @endif
  </div>
</div>