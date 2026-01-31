@props(['name', 'label', 'id' => 0, 'dia' => '', 'mes' => '', 'anno' => ''])

<div class="form-group mt-2">
  <label class="form-label">{{ $label }}</label>
  <div class="input-group">

    {{-- Día --}}
    <input type="number" name="dia_{{ $name }}" id="dia_{{ $name }}" 
      class="form-control @error('dia_'.$name) is-invalid @enderror"
      value="{{ old('dia_'.$name, ($id > 0 ? $dia : '')) }}" placeholder="Día">

    {{-- Mes --}}
    <select name="mes_{{ $name }}" id="mes_{{ $name }}" class="form-control @error('mes_'.$name) is-invalid @enderror">
      <option selected disabled value="">Mes</option>
      @php
      $meses = [ 0 => 'Semana de año nuevo', 1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
      4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio',
      8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre' ];
      @endphp
      @foreach($meses as $val => $nombre)
      <option value="{{ $val }}" {{ old('mes_'.$name, ($id > 0 ? $mes : '')) == "$val" ? 'selected' : '' }}>
        {{ $nombre }}
      </option>
      @endforeach
    </select>

    {{-- Año --}}
    <input type="number" name="anno_{{ $name }}" id="anno_{{ $name }}" 
      class="form-control @error('anno_'.$name) is-invalid @enderror"
      value="{{ old('anno_'.$name, ($id > 0 ? $anno : '')) }}" placeholder="Año">
  </div>

  {{-- Errores consolidados --}}
  @error('dia_'.$name) <small class="text-danger d-block">{{ $message }}</small> @enderror
  @error('mes_'.$name) <small class="text-danger d-block">{{ $message }}</small> @enderror
  @error('anno_'.$name) <small class="text-danger d-block">{{ $message }}</small> @enderror
</div>