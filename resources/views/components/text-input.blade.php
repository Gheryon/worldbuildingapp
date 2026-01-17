@props(['name','label','value' => '','type' => 'text','placeholder' => '','required' => false])

<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
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