@props(['name', 'label', 'orden' => 'asc'])

<li class="nav-item ml-2">
  <select id="order" class="form-control ml-2" name="order">
    <option disabled value="ASC">Orden</option>
    <option value="asc" {{ $orden == 'asc' ? 'selected' : '' }}>Ascendente</option>
    <option value="desc" {{ $orden == 'desc' ? 'selected' : '' }}>Descendente</option>
  </select>
</li>