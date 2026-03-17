@props(['items', 'title', 'route', 'name' => '', 'placeholder' => ''])

<div class="card col ml-1">
  <div class="card-header">
    <h5 class="card-title">{{ $title }}</h5>
  </div>
  <div class="card-body overflow-auto" style="height: 300px;">
    <table class="table table-sm table-hover table-striped table-dark">
      <thead class="bg-dark">
        <tr>
          <th>Nombre</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td>{{ $item->nombre }}</td>
          <td>
            <button data-id="{{$item->id}}" data-nombre="{{$item->nombre}}" data-tipo="{{$name}}" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
            <button data-id="{{$item->id}}" data-nombre="{{$item->nombre}}" data-tipo="{{$name}}" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="2" class="text-center text-muted">No hay registros disponibles.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    <label for="nuevo_tipo_{{ $name }}" class="form-label">Añadir nuevo</label>
    <form id="form-add-tipo-{{ $name }}" class="row" action="{{ route('config.store_generic', ['type' => $name]) }}" method="POST">
      @csrf
      <div class="col">
        {{-- Importante: El nombre del input debe coincidir con lo que el controlador espera --}}
        <input type="text" value="{{ old('nuevo_tipo_'.$name) }}" name="nuevo_tipo_{{$name}}" class="form-control" id="nuevo_tipo_{{$name}}" placeholder="{{ $placeholder }}">
        @error('nuevo_tipo_'.$name)
        <small style="color: red">{{ $message }}</small>
        @enderror
      </div>
      <div class="col-3 align-bottom">
        <button type="submit" class="btn btn-primary">Añadir</button>
      </div>
    </form>
  </div>
</div>