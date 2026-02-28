@props(['title', 'route', 'icon', 'bg'])

<div class="col-lg-3 col-6">
  <div class="small-box {{ $bg ?? 'bg-dark' }}">
    <div class="inner">
      <h3>{{ $title }}</h3>
      <p>Gestionar</p>
    </div>
    <div class="icon">
      <i class="fa-solid {{ $icon }}"></i>
    </div>
    <a href="{{ route($route) }}" class="small-box-footer">
      Ver listado <i class="fas fa-arrow-circle-right ml-1"></i>
    </a>
  </div>
</div>