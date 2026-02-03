@props(['especie'])

<div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden position-relative">
  <div class="position-absolute" style="top: 10px; right: 10px; z-index: 2;">
    <span class="badge shadow-sm px-2 py-1 {{ $especie->rareza == 'Legendaria' ? 'bg-warning text-dark' : 'bg-dark text-white' }}">
      {{ $especie->rareza ?? 'Común' }}
    </span>
  </div>

  <div class="card-body p-4">
    <div class="text-center mb-3">
      <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 55px; height: 55px;">
        <i class="fas {{ $especie->locomocion == 'Acuática' ? 'fa-fish' : ($especie->locomocion == 'Aérea' ? 'fa-dove' : 'fa-paw') }} fa-2x text-primary"></i>
      </div>
      <h5 class="font-weight-bold mb-0">{{ $especie->nombre }}</h5>
      <p class="text-muted small text-uppercase mb-0">
        {{ $especie->reino }} | {{ $especie->clase_taxonomica }}
      </p>
      <span class="badge badge-pill {{ $especie->estatus == 'Extinta' ? 'badge-danger' : 'badge-success' }} x-small mt-2">
        {{ $especie->estatus }}
      </span>
    </div>

    <div class="row no-gutters text-center border-top border-bottom py-2 bg-light-soft">
      <div class="col-4 border-right" title="Peso medio">
        <small class="text-muted d-block">Peso</small>
        <span class="font-weight-bold small text-dark">{{ $especie->peso ?: '—' }}</span>
      </div>
      <div class="col-4 border-right" title="Altura o Longitud">
        <small class="text-muted d-block">Tamaño</small>
        <span class="font-weight-bold small text-dark">{{ $especie->altura ?: $especie->longitud ?: '—' }}</span>
      </div>
      <div class="col-4" title="Esperanza de vida">
        <small class="text-muted d-block">Vida</small>
        <span class="font-weight-bold small text-dark">{{ $especie->edad ?: '—' }}</span>
      </div>
    </div>

    <div class="mt-3">
      <div class="d-flex align-items-center mb-2">
        <i class="fas fa-utensils text-muted mr-2 w-20px text-center"></i>
        <span class="small"><strong>Dieta:</strong> {{ $especie->dieta ?: 'No especificada' }}</span>
      </div>
      <div class="d-flex align-items-center mb-2">
        <i class="fas fa-users text-muted mr-2 w-20px text-center"></i>
        <span class="small"><strong>Social:</strong> {{ $especie->organizacion_social ?: 'Variable' }}</span>
      </div>
      <div class="d-flex align-items-center">
        <i class="fas fa-skull-crossbones text-muted mr-2 w-20px text-center"></i>
        <span class="small"><strong>Mortalidad:</strong> {{ $especie->mortalidad ?: 'N/A' }}</span>
      </div>
    </div>
  </div>

  <div class="card-footer bg-white border-0 text-center pb-3">
    <hr class="mx-4 my-0 mb-3">
    <div class="btn-group border rounded-pill overflow-hidden shadow-sm">
        <a href="{{ route('especie.show', $especie->id) }}" class="btn btn-white btn-sm px-3 border-right text-muted hover-primary" title="Ver">
            <i class="fas fa-eye text-info"></i>
        </a>
        <a href="{{ route('especie.edit', $especie->id) }}" class="btn btn-white btn-sm px-3 border-right text-muted hover-success" title="Editar">
            <i class="fas fa-edit text-success"></i>
        </a>
        <button class="borrar btn btn-white btn-sm px-3 text-muted hover-danger" data-id="{{ $especie->id }}" data-nombre="{{ $especie->nombre }}" data-toggle="modal" data-target="#eliminar-especie" title="Eliminar">
            <i class="fas fa-trash text-danger"></i>
        </button>
    </div>
</div>
</div>