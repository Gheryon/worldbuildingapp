@props(['conflicto'])

<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-4">
  <div class="card h-100 border-0 shadow-lg bg-white rounded-lg overflow-hidden">
    <div class="card-header border-0 bg-white pt-4">
      <div class="badge {{ $conflicto->es_conflicto_magico ? 'badge-warning' : 'badge-danger' }} px-3 py-2 mb-2 shadow-sm">{{ $conflicto->tipoConflicto->nombre }}</div>
      @if($conflicto->es_conflicto_magico)
      <i class="fas fa-hat-wizard text-primary-custom ml-2" title="Conflicto Mágico"></i>
      @endif
      <h4 class="font-weight-bold text-dark">{{ $conflicto->nombre }}</h4>
    </div>
    <div class="card-body pt-0">
      <div class="article-body text-secondary small" style="line-height: 1.6;">
        {!! clean(Str::limit($conflicto->descripcion, 120)) !!}
      </div>
    </div>
    <div class="card-footer bg-light border-0 justify-content-center d-flex">
      <div class="btn-group border rounded-pill overflow-hidden shadow-sm">
        <a href="{{ route('conflicto.show', $conflicto->id) }}" class="btn btn-white btn-sm px-3 border-right text-muted hover-primary" title="Ver">
            <i class="fas fa-eye text-info"></i>
        </a>
        <a href="{{ route('conflicto.edit', $conflicto->id) }}" class="btn btn-white btn-sm px-3 border-right text-muted hover-success" title="Editar">
            <i class="fas fa-edit text-success"></i>
        </a>
        <button class="borrar btn btn-white btn-sm px-3 text-muted hover-danger" data-id="{{ $conflicto->id }}" data-nombre="{{ $conflicto->nombre }}" data-toggle="modal" data-target="#eliminar-conflicto" title="Eliminar">
            <i class="fas fa-trash text-danger"></i>
        </button>
    </div>
    </div>
  </div>
</div>
