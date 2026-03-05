@props(['lugar'])

<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-4">
  <div class="card h-100 shadow-sm hover-shadow transition-all border-0">
    {{-- Badge de Tipo --}}
    <div class="position-absolute mt-2 ml-2" style="z-index: 10;">
      <span class="badge badge-dark opacity-85 px-3 py-2">
        <i class="fas fa-map-marker-alt mr-1"></i> {{ $lugar->tipo_lugar ?? $lugar->tipo->nombre }}
      </span>
    </div>

    {{-- Cuerpo de la Card --}}
    <div class="card-body pt-5">
      <h4 class="card-title font-weight-bold text-truncate w-100 mb-2" title="{{ $lugar->nombre }}">
        {{ $lugar->nombre }}
      </h4>

      <div class="mb-3">
        <p class="text-muted small mb-1">
          <i class="fas fa-biohazard mr-1"></i> <strong>Peligro:</strong> {{ $lugar->nivel_peligro ?? 'No definido' }}
        </p>
        <p class="text-muted small">
          <i class="fas fa-walking mr-1"></i> <strong>Acceso:</strong> {{ $lugar->dificultad_acceso ?? 'Normal' }}
        </p>
      </div>

      <div class="card-text text-secondary mb-1" style="font-size: 0.9rem; line-height: 1.4; height: 3.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; line-clamp: 3;">
        {!! strip_tags($lugar->descripcion_breve) !!}
      </div>
    </div>

    {{-- Footer con Botones --}}
    <div class="card-footer bg-white border-top-0 pb-3">
      <div class="d-flex justify-content-between gap-2">
        <a href="{{ route('lugar.show', $lugar->id) }}" class="btn btn-outline-info btn-sm flex-fill" title="Ver detalles">
          <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('lugar.edit', $lugar->id) }}" class="btn btn-outline-success btn-sm flex-fill mx-1" title="Editar">
          <i class="fas fa-edit"></i>
        </a>
        <button type="button"
          class="borrar btn btn-outline-danger btn-sm flex-fill"
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