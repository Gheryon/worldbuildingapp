@props(['name','label','value' => '','type' => 'text','placeholder' => '','required' => false, 'icon' => null])

<div class="form-group mt-2">
  <label for="{{ $name }}">
    @if($icon)<i class="fas {{ $icon }} mr-1"></i>@endif{{ $label }}
  </label>
  <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
    value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge([ 'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '') ]) }}>

  @error($name)
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
  </span>
  @enderror
</div>