@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Línea cronológica</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <button type="button" title="Nuevo evento" class="nuevo btn btn-dark" data-toggle="modal" data-target="#nuevo-evento">Nuevo evento</button>
</li>
<li class="nav-item ml-2">
  <select id="order_timeline" class="form-control ml-2" name="order_timeline">
    <option selected disabled value="ASC">Orden</option>
    <option value="asc">Ascendente</option>
    <option value="desc">Descendente</option>
  </select>
</li>
@endsection

@section('content')
<div class="modal fade" id="eliminar-evento" tabindex="-1" role="dialog" aria-labelledby="eliminar-evento" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h3 class="card-title">Eliminar evento</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-borrar-evento" class="text-center" action="{{route('evento.destroy')}}" method="POST">
          @csrf
          @method('DELETE')
          <div class="card-body">
            <div class="input-group mb-3">
              <p> ¿Borrar evento: <span id="nombre-evento-borrar"> </span>?</p>
              <input type="hidden" id="id_evento" name="id_evento">
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn bg-gradient-danger">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="nuevo-evento" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Nuevo evento" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="nuevoEventoLabel">Nuevo evento</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-evento" class="col-md-auto" action="{{route('evento.store')}}" method="POST">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col">
                <input type="hidden" name="id_editar" id="id_editar">
                <label for="nombre" class="form-label">Nombre del evento</label>
                <input type="text" name="nombre" class="form-control nombreEvento" id="nombre" placeholder="Crisis de la sal" required>
                <div class="invalid-feedback">
                  Nombre necesario.
                </div>
              </div>
              <div class="col-md-3">
                <label for="inputFecha" class="form-label">Fecha</label>
                <div id="inputFecha" class="input-group mb-2">
                  <input type="text" name="dia" id="dia" class="form-control" placeholder="Dia" aria-label="Dia">
                  <input type="text" name="mes" id="mes" class="form-control" placeholder="Mes" aria-label="Mes">
                  <input type="text" name="anno" id="anno" class="form-control" placeholder="Año" aria-label="Año" required>
                </div>
              </div>
              <div class="col-md-4">
                <label for="select_timeline" class="form-label">Línea temporal</label>
                <select class="form-select form-control" name="select_timeline" id="select_timeline" required>
                  @foreach($timelines as $timeline)
                  <option value="{{$timeline->id}}">{{$timeline->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col">
              <label for="descripcion" class="form-label">Descripción del evento</label>
              <textarea class="form-control summernote" name="descripcion" id="descripcion" rows="1" aria-label="With textarea"></textarea>
              <div class="invalid-feedback">
                Descripción necesaria.
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="button" id="cancelar-crear-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
            <button type="button" id="volver-crear-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Volver</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <!-- The time line -->
    <div class="timeline">
      @if (Arr::has($eventos, 'error.error'))
      <div class="text-center">No se encontraron eventos.</div>
      {{Arr::get($eventos, 'error.error')}}
    </div>
    @else
    @foreach($eventos as $evento)
    <!-- timeline time label -->
    <div class="time-label">
      @if($evento->dia==0&&$evento->mes==0)
      <span class="bg-dark">{{$evento->anno}}</span>
      @else
      <span class="bg-dark">{{$evento->dia}}/{{$evento->mes}}/{{$evento->anno}}</span>
      @endif
    </div>
    <!-- /.timeline-label -->
    <!-- timeline item -->
    <div>
      <i class="fas fa-envelope bg-blue"></i>
      <div class="timeline-item">
        @switch($evento->tipo)
        @case('nace_personaje')
        <h3 class="timeline-header">Nacimiento de <a href="{{route('personaje.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('muere_personaje')
        <h3 class="timeline-header">Muerte de <a href="{{route('personaje.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('ini_conflicto')
        <h3 class="timeline-header">Comienzo de <a href="{{route('conflicto.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        <div class="timeline-body">
          {!!$evento->descripcion!!}
        </div>
        @break

        @case('fin_conflicto')
        <h3 class="timeline-header">Finalización de <a href="{{route('conflicto.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        <div class="timeline-body">
          {!!$evento->descripcion!!}
        </div>
        @break

        @case('ini_asentamiento')
        <h3 class="timeline-header">Fundación de <a href="{{route('asentamiento.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('fin_asentamiento')
        <h3 class="timeline-header">Destrucción de <a href="{{route('asentamiento.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('ini_organizacion')
        <h3 class="timeline-header">Fundación de <a href="{{route('organizacion.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('fin_organizacion')
        <h3 class="timeline-header">Destrucción de <a href="{{route('organizacion.show',$evento->id)}}" role="button" title="Ver {{$evento->nombre}}" class=""><b>{{$evento->nombre}}</b></a></h3>
        @break

        @case('fecha_actual')
        <h3 class="timeline-header">Fecha actual: 
          @if($evento->dia==0&&$evento->mes==0)
          <span >{{$evento->anno}}</span>
          @else
          <span ><b>{{$evento->dia}}/{{$evento->mes}}/{{$evento->anno}}</b></span>
          @endif
        </h3>
        @break

        @default
        <h3 class="timeline-header">{{$evento->nombre}}</h3>
        <div class="timeline-body">
          {!!$evento->descripcion!!}
        </div>
        @endswitch

        @if ($evento->tipo=='BUTTONS')
        <div class="timeline-footer">
          <button data-id="{{$evento->id}}" type="button" class="editar btn btn-primary btn-sm" data-toggle="modal" data-target="#nuevo-evento">Editar</button>
          <button data-id="{{$evento->id}}" data-nombre="{{$evento->nombre}}" class="borrar btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminar-evento">Eliminar</button>
        </div>
        @endif
      </div>
    </div>
    <!-- END timeline item -->
    @endforeach
    @endif
    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>
</div>
<!-- /.col -->
</div>

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/timelines.js')}}"></script>
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    $('#order_timeline').val('{{$orden}}');
    $('#filter_timeline').val('{{$cronologia}}');

    $(document).on('change', '#order_timeline', function() {
      //$('#order_timeline').on('change', function(){ <--por lo que sea, no funciona así
      orden = this.value;
      cronologia = "{{$cronologia}}";
      let url = "{{ route('timelines.index', ['orden'=>'_orden', 'cronologia'=>'_cronologia']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_cronologia', cronologia);
      document.location.href = url;
    });

    $(document).on('change', '#filter_timeline', function() {
      cronologia = this.value;
      orden = "{{$orden}}";
      let url = "{{ route('timelines.index', ['orden'=>'_orden', 'cronologia'=>'_cronologia']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_cronologia', cronologia);
      document.location.href = url;
    });
  });
</script>

<script>
  @if(Session::has('message'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": false,
    "progressBar": false,
    "showDuration": 0,
    "preventDuplicates": true,
  }
  console.log("{{ session('error') }}");
  toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.warning("{{ session('warning') }}");
  @endif
</script>
@endsection