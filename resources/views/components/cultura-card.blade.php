@props(['cultura'])
<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 mb-3">
  <div class="card shadow-sm card-dark card-outline h-100" style="background-color: #f0e8d8;">
    <div class="card-header bg-transparent border-bottom-0 pb-0 pt-3 px-3" style="background-color: inherit;">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h5 class="font-weight-bold mb-0">{{ $cultura->nombre }}</h5>
          <span class="small text-muted">{{ $cultura->gentilicio }}</span>
        </div>
        <div>
          <span class="badge badge-pill badge-{{ $cultura->estatus?->value == 'extinta' ? 'danger' : ($cultura->estatus?->value == 'en declive' ? 'warning' : 'success') }} px-3 py-1">
            {{ $cultura->estatus?->label() }}
          </span>
          <span class="badge badge-pill badge-light border px-3 py-1 ml-1">{{ $cultura->tipo_territorio?->label() ?? '—' }}</span>
        </div>
      </div>
    </div>
    <div class="card-body pt-2 px-3">
      <div class="d-flex align-items-center small" style="gap: 2rem;">
        <div><i class="fas fa-wand-magic-sparkles fa-fw text-muted"></i> <strong>Magia:</strong> {{ $cultura->actitud_magia?->label() ?? '—' }}</div>
        <div><i class="fas fa-people-arrows fa-fw text-muted"></i> <strong>Forasteros:</strong> {{ $cultura->actitud_forasteros?->label() ?? '—' }}</div>
        <div><i class="fas fa-layer-group fa-fw text-muted"></i> <strong>Importancia:</strong> {{ ucfirst($cultura->categoria ?? '—') }}</div>
      </div>
    </div>
    <div class="card-footer bg-transparent pt-0 pb-2 px-3 text-center" style="background-color: inherit;">
      <a href="{{ route('culturas.show', $cultura->id) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-eye"></i></a>
      <a href="{{ route('culturas.edit', $cultura->id) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-pen"></i></a>
      <button class="borrar btn btn-sm btn-outline-danger" data-id="{{ $cultura->id }}" data-nombre="{{ $cultura->nombre }}" data-url="{{ route('culturas.destroy', $cultura->id) }}" data-toggle="modal" data-target="#eliminar-cultura"><i class="fas fa-trash"></i></button>
    </div>
  </div>
</div>
