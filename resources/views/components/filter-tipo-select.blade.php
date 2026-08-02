@props([
'tipos' => collect(),
'tipoId' => 0,
'name' => 'filter_tipo',
'label' => 'Filtrar tipo',
'placeholder' => 'Filtrar tipo',
'todosLabel' => 'Todos',
])

<li class="nav-item ml-2">
  <label for="{{ $name }}" class="sr-only">{{ $label }}</label>
  <select id="{{ $name }}" class="form-control ml-2" name="{{ $name }}" aria-label="{{ $label }}">
    <option selected disabled value="0">{{ $placeholder }}</option>
    <option value="0" {{ $tipoId == 0 ? 'selected' : '' }}>{{ $todosLabel }}</option>
    @forelse($tipos as $tipo)
    @if(is_object($tipo))
    <option value="{{ $tipo->id }}" {{ $tipoId == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
    @endif
    @empty
    <option value="0" disabled>Sin tipos disponibles</option>
    @endforelse
  </select>
</li>