@props(['fecha' => null])

<li class="nav-item ml-2">
  <select id="filtro_fecha" class="form-control ml-2" name="fecha">
    <option value="" selected disabled>Ordenar por Fecha</option>
    {{-- Opción para limpiar el filtro de fecha y volver al alfabético --}}
    <option value="">(Sin orden de fecha)</option>
    <option value="desc" {{ $fecha == 'desc' ? 'selected' : '' }}>Más recientes</option>
    <option value="asc" {{ $fecha == 'asc' ? 'selected' : '' }}>Más antiguos</option>
  </select>
</li>