@props(['organizacion'])
<div class="col-md-4 col-lg-2 mb-4">
  <div class="card h-100 shadow" style="border: 1px solid #d4c4a8; background-color: #fcf9f2;">
    <div class="card-body text-center p-3 pb-0">
      <img src="{{ asset("storage/escudos/{$organizacion->escudo}") }}" class="img-fluid mb-2" style="max-height: 130px; object-fit: contain;">
      <h5 class="font-weight-bold" style="color: #4a3728;">{{ $organizacion->nombre }}</h5>
      <span class="badge badge-warning mb-2">{{ $organizacion->tipo }}</span>
      <p class="small text-muted font-italic mb-2">{{ $organizacion->estatus ?? 'Activa' }}</p>
      <hr>
      <div class="text-left small mb-2">
        <p class="mb-0 text-truncate"><b>Líder:</b> 
          @if($organizacion->lider_id)
            <a href="{{ route('personajes.show', $organizacion->lider_id) }}">{{ $organizacion->nombre_lider }}</a>
          @else
            Desconocido
          @endif
        </p>
        <p class="mb-0 text-truncate"><b>Controlado por:</b> 
          @if($organizacion->organizacion_padre_id)
            <a href="{{ route('organizaciones.show', $organizacion->organizacion_padre_id) }}">{{ $organizacion->nombre_padre }}</a>
          @else
            N/A
          @endif
        </p>
      </div>
      <p class="small text-justify mb-0">{!! Str::limit(strip_tags($organizacion->descripcion_breve), 90) !!}</p>
    </div>
    <div class="card-footer bg-transparent border-0 text-center pt-1 pb-2">
      <a href="{{ route('organizaciones.show', $organizacion->id) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-eye"></i></a>
      <a href="{{ route('organizaciones.edit', $organizacion->id) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-pen"></i></a>
      <button class="borrar btn btn-sm btn-outline-danger" data-id="{{ $organizacion->id }}" data-url="{{ route('organizaciones.destroy', $organizacion->id) }}" data-toggle="modal" data-target="#eliminar-organizacion"><i class="fas fa-trash"></i></button>
    </div>
  </div>
</div>