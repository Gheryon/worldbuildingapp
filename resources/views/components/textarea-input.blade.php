@props(['name', 'label', 'value' => '', 'rows' => 4, 'class' => 'summernote-lite', 'icon' => null])

<div class="form-group mt-2">
  <label for="{{ $name }}" class="form-label">
    @if($icon)<i class="fas {{ $icon }} mr-1"></i>@endif{{ $label }}
  </label>
  <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid ' : '') . $class]) }} aria-label="{{$label}}">{{ old($name, $value ?? '') }}</textarea>
  @error($name)
  <span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
  </span>
  @enderror
</div>