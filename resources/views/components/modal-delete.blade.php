@props([
'id' => 'modal-delete', {{-- ID único para el modal --}}
'route' => '', {{-- Ruta del formulario (ej: especie.destroy) --}}
'title' => 'Confirmar eliminación',
'message' => '¿Estás seguro de que deseas eliminar este registro?'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="{{ $id }}Label">
          <i class="fas fa-exclamation-triangle mr-2"></i> {{ $title }}
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ $route }}" method="POST" id="form-confirmar-borrar">
        @csrf
        @method('DELETE')

        <div class="modal-body">
          <p>{{ $message }}</p>
          {{-- El nombre del registro se inyectará aquí --}}
          <div class="alert alert-light border">
            <strong id="nombre-borrar" class="text-danger">Cargando...</strong>
          </div>

          {{-- Inputs ocultos para el controlador --}}
          <input type="hidden" name="id_borrar" id="id_borrar">
          <input type="hidden" name="nombre_borrado" id="nombre_borrado">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-danger" id="confirmar-borrar-button">
            <i class="fas fa-trash-alt mr-1"></i> Confirmar Eliminación
          </button>
        </div>
      </form>
    </div>
  </div>
</div>