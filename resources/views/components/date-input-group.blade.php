@props(['name', 'label', 'idPrefix'])

<div class="form-group mt-2">
  <label class="form-label">{{ $label }}</label>
  <div class="input-group">
    <input type="hidden" name="id_{{ $name }}" value="0">

    <input type="number" name="d{{ $name }}" id="d_{{ $idPrefix }}" class="form-control @error('d'.$name) is-invalid @enderror"
      value="{{ old('d'.$name) }}" placeholder="Día">

    <select name="m{{ $name }}" id="m_{{ $idPrefix }}" class="form-control @error('m'.$name) is-invalid @enderror">
      <option selected disabled value="">Mes</option>
      @php
      $meses = [ 0 => 'Semana de año nuevo', 1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
      4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio',
      8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre' ];
      @endphp
      @foreach($meses as $val => $nombre)
      <option value="{{ $val }}" {{ old('m'.$name) == "$val" ? 'selected' : '' }}>
        {{ $nombre }}
      </option>
      @endforeach
    </select>

    <input type="number" name="a{{ $name }}" id="a_{{ $idPrefix }}" class="form-control @error('a'.$name) is-invalid @enderror"
      value="{{ old('a'.$name) }}" placeholder="Año">
  </div>

  {{-- Errores consolidados --}}
  @error('d'.$name) <small class="text-danger d-block">{{ $message }}</small> @enderror
  @error('a'.$name) <small class="text-danger d-block">{{ $message }}</small> @enderror
</div>