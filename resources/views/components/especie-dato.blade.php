@props(['label', 'value', 'icon' => null])

@if($value)
<li {{ $attributes->merge(['class' => 'list-group-item d-flex justify-content-between align-items-center py-3 border-light']) }}>
  <div class="d-flex align-items-center">
    <div class="icon-box mr-3 text-dark text-center" style="width: 25px;">
      <i class="fas {{ $icon ?? 'fa-info-circle' }}"></i>
    </div>
    <span class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">
      {{ $label }}
    </span>
  </div>
  <span class="text-dark font-weight-bold ml-2 text-right">
    {{ $value }}
  </span>
</li>
@endif