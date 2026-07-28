@props(['enum', 'name', 'label', 'selected' => '', 'placeholder' => 'Elegir', 'required' => false, 'sort' => false])

<div class="form-group mt-2">
  <label for="{{ $name }}">{{ $label }}</label>
  <select name="{{ $name }}" id="{{ $name }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'form-select form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>
    <option selected disabled value="">{{ $placeholder }}</option>
    <!-- Si $prop es true, ordena el enum alfabéticamente, si no, no -->
    @php $casos = $sort ? collect($enum::cases())->sortBy->label()->all() : $enum::cases(); @endphp
    @foreach($casos as $case)
    <option value="{{ $case->value }}" {{ old($name, $selected) == $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
    @endforeach
  </select>
  @error($name)
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
  </span>
  @enderror
</div>
