@props(['lugar'])

<div class="col-md-6 col-lg-4 col-xl-3 mb-4">
  <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
    <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 160px; background: linear-gradient(45deg, #2b2b2b, #591717);">
      <i class="fas fa-map-marked-alt fa-3x text-white-50"></i>
    </div>
    <div class="card-body">
      <span class="badge badge-danger mb-2">{{ $lugar->tipo->nombre }}</span>
      <h5 class="font-weight-bold mb-1">{{ $lugar->nombre }}</h5>
      <p class="text-muted small mb-3">{!! Str::limit(strip_tags($lugar->descripcion_breve), 80) !!}</p>
      <div class="d-flex justify-content-between align-items-center">
        <span class="small text-muted"><i class="fas fa-skull-crossbones mr-1"></i> Peligro: {{ $lugar->nivel_peligro ?? 'Seguro' }}</span>
        <div class="btn-group">
          <a href="{{ route('lugar.show', $lugar->id) }}" title="Ver detalles" class="btn btn-sm btn-outline-secondary border-0"><i class="fas fa-eye"></i></a>
          <a href="{{ route('lugar.edit', $lugar->id) }}" title="Editar" class="btn btn-sm btn-outline-secondary border-0"><i class="fas fa-pen"></i></a>
          <button type="button"
          class="borrar btn btn-sm btn-outline-secondary border-0"
          data-id="{{ $lugar->id }}"
          data-nombre="{{ $lugar->nombre }}"
          data-toggle="modal"
          data-target="#eliminar-lugar"
          title="Eliminar">
          <i class="fas fa-trash"></i>
        </button>
        </div>
      </div>
    </div>
  </div>
</div>