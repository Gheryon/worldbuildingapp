@extends('layouts.index')

@section('title')
<title id="title">Línea cronológica</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <button type="button" title="Nuevo evento" class="nuevo btn btn-dark" data-toggle="modal" data-target="#nuevo-evento">Nuevo evento</button>
</li>
<li class="nav-item ml-2">
  <select id="tipo" class="form-control ml-2" name="tipo">
    <option selected disabled value="ASC">Tipo</option>
    <option value="principal">Principales</option>
    <option value="asentamientos">Asentamientos</option>
    <option value="conflictos">Conflictos</option>
    <option value="crisis">Crisis</option>
    <option value="personajes">Personajes</option>
    <option value="organizaciones">Organizaciones</option>
  </select>
</li>
<li class="nav-item ml-2">
  <select id="categoria" class="form-control ml-2" name="categoria">
    <option selected disabled value="ASC">Categoría</option>
    <option value="local">Local</option>
    <option value="regional">Regional</option>
    <option value="continental">Continental</option>
    <option value="global">Global</option>
    <option value="universal">Universal</option>
  </select>
</li>

<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('timelines.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('content')

<!-- Modal -->
<div class="modal fade" id="nuevo-evento" data-backdrop="static" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="card card-dark mb-0">
        <div class="card-header border-0">
          <h5 class="card-title" id="nuevoEventoLabel"><i class="fas fa-feather-alt mr-2"></i> Nuevo evento</h5>
          <button data-dismiss="modal" class="close text-white"><span>&times;</span></button>
        </div>
        <form id="form-evento" action="{{route('evento.store')}}" method="POST">
          @csrf
          <input type="hidden" name="id_editar" id="id_editar" value="{{ old('id_editar') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label><i class="fas fa-signature mr-1"></i> Nombre del evento</label>
                  <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: La Caída del Imperio" value="{{ old('nombre') }}" required>
                  @error('nombre')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <label><i class="fas fa-calendar-alt mr-1"></i> Fecha (D/M/A)</label>
                <div class="input-group">
                  <input type="number" name="dia" class="form-control  @error('dia') is-invalid @enderror" value="{{ old('dia') }}" placeholder="01">
                  @error('dia')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                  <input type="number" name="mes" class="form-control  @error('mes') is-invalid @enderror" value="{{ old('mes') }}" placeholder="01">
                  @error('mes')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                  <input type="number" name="anno" class="form-control  @error('anno') is-invalid @enderror" value="{{ old('anno') }}" placeholder="1250" required>
                  @error('anno')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fas fa-tags mr-1"></i> Tipo de evento</label>
                  <select id="form_tipo" class="form-control @error('form_tipo') is-invalid @enderror" name="form_tipo">
                    <option value="general" selected>General</option>
                    <option value="crisis">Crisis / Catástrofe natural</option>
                    <option value="epidemia">Epidemia</option>
                    <option value="logro">Logro / Descubrimiento</option>
                    <option value="politico">Evento político</option>
                    <option value="religioso">Evento religioso</option>
                  </select>
                  @error('form_tipo')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fas fa-globe mr-1"></i> Escala de relevancia</label>
                  <select id="form_categoria" class="form-control @error('form_categoria') is-invalid @enderror" name="form_categoria">
                    <option value="local">Local</option>
                    <option value="regional">Regional</option>
                    <option value="continental">Continental</option>
                    <option value="global">Global</option>
                    <option value="universal">Universal</option>
                  </select>
                  @error('form_categoria')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label><i class="fas fa-align-left mr-1"></i> Descripción</label>
              <textarea class="form-control @error('descripcion') is-invalid @enderror summernote" name="descripcion" id="descripcion" required>{{ old('descripcion') }}</textarea>
              @error('descripcion')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
              @enderror
            </div>
          </div>
          <div class="card-footer bg-light d-flex justify-content-end">
            <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Descartar</button>
            <button type="submit" id="submit-crear-button" class="btn btn-success px-4 shadow-sm">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="timeline">
        @forelse($eventos as $evento)
        {{-- Etiqueta de fecha --}}
        <div class="time-label">
          <span class="bg-dark shadow-sm">
            @if($evento->dia == 0 && $evento->mes == 0)
            {{ $evento->anno }}
            @else
            {{ sprintf('%02d/%02d/%d', $evento->dia, $evento->mes, $evento->anno) }}
            @endif
          </span>
        </div>

        {{-- Llamada al componente --}}
        <x-evento-item :evento="$evento" />

        @empty
        <div class="alert alert-info text-center shadow-sm">
          <i class="fas fa-info-circle mr-2"></i> No se han encontrado registros en este periodo cronológico.
        </div>
        @endforelse

        <div><i class="fas fa-infinity bg-gray"></i></div>
      </div>

      <div class="mt-4 d-flex justify-content-center">
        {{ $eventos->links() }}
      </div>
    </div>
  </div>
</div>

<x-modal-delete
  id="eliminar-evento"
  :route="route('evento.destroy')"
  message="Estás a punto de eliminar el siguiente evento de forma permanente:" />

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const tipo = $('#tipo').val();
      const categoria = $('#categoria').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (tipo && tipo !== '0') params.append('tipo', tipo);
      if (categoria && categoria !== '0') params.append('categoria', categoria);

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('timelines.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#tipo', redirigirConFiltros);
    $(document).on('change', '#categoria', redirigirConFiltros);
  });
</script>
<script>
  //Objeto global de configuración
  window.AppConfig = {
    baseUrl: "{{ url('/') }}",
    routeEdit: "{{ route('evento.edit', ['id' => ':id']) }}",
    routeUpdate: "{{ route('evento.update', ['id' => ':id']) }}"
  };
</script>
<script>
  $(document).ready(function() {
    // Si Laravel devuelve errores de validación, reabrimos el modal automáticamente
    @if($errors->any())
    $('#nuevo-evento').modal('show');
    @endif
  });
</script>
@endsection