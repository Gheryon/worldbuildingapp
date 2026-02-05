@extends('layouts.index')

@section('title')
<title id="title">Editar {{$organizacion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('organizaciones.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$organizacion->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-edit-organization" class="position-relative needs-validation" action="{{route('organizacion.update', $organizacion->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success px-5 shadow-sm">Guardar</button>
      </div>
    </div>

    {{-- Sección de datos básicos y escudo --}}
    <div class="card card-outline card-dark mt-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
            <div class="row">
              <div class="col-md">
                <x-text-input name="nombre" label="Nombre" placeholder="Ej: La Compañía del Anillo, El Imperio Romano, etc." :value="$organizacion->nombre" required />
              </div>
              <div class="col-md">
                <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Español, Narniano, etc." :value="$organizacion->gentilicio" />
              </div>
              <div class="col-md">
                <x-text-input name="capital" label="Capital" placeholder="Ej: Minas Tirith, Córdoba, etc." :value="$organizacion->capital" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="select_tipo" class="form-label">Tipo de organización</label>
                <select class="form-select form-control" name="select_tipo" id="select_tipo" @if($tipo_organizacion->count()>0)required @endif>
                  <option selected disabled value="">Elegir</option>
                  @if($tipo_organizacion->count()>0)
                  @foreach($tipo_organizacion as $tipo)
                  <option value="{{$tipo->id}}" {{ old('select_tipo', $organizacion->tipo_organizacion_id) == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                  @endif
                </select>
                @error('select_tipo')
                <small style="color: red">{{$message}}</small>
                @enderror
              </div>
              <div class="col-md">
                <label for="select_lider" class="form-label">Soberano</label>
                <select class="form-select form-control" name="select_lider" id="select_lider">
                  <option selected disabled value="">Elegir</option>
                  @foreach($personajes as $id => $nombre)
                  <option value="{{$id}}" {{ old('select_lider', $organizacion->lider_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md">
                <label for="select_organizacion_padre" class="form-label">Controlado por</label>
                <select class="form-select form-control" name="select_organizacion_padre" id="select_organizacion_padre">
                  <option selected disabled value="">Elegir</option>
                  @foreach($paises as $id => $nombre)
                  <option value="{{$id}}" {{ old('select_organizacion_padre', $organizacion->organizacion_padre_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <x-date-input-group name="fundacion" label="Fecha de fundación" :id="$organizacion->fundacion_id" :dia="$organizacion->fecha_fundacion->dia ?? ''" :mes="$organizacion->fecha_fundacion->mes ?? ''" :anno="$organizacion->fecha_fundacion->anno ?? ''"/>
              </div>
              <div class="col-md">
                <x-date-input-group name="disolucion" label="Fecha de disolución" :id="$organizacion->disolucion_id" :dia="$organizacion->fecha_disolucion->dia ?? ''" :mes="$organizacion->fecha_disolucion->mes ?? ''" :anno="$organizacion->fecha_disolucion->anno ?? ''" />
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <x-text-input name="lema" label="Lema" placeholder="Ej: Justicia para todos." :value="$organizacion->lema" />
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="religiones" class="form-label mt-2">Religiones presentes</label>
                  <select class="select2" multiple="multiple" name="religiones[]" id="religiones" data-placeholder="Selecciona religiones...">
                    @php
                    // Determinamos los IDs seleccionados: prioridad a old() tras error de validación,
                    // si no, usamos los IDs que ya tiene la organización en la BD.
                    $selectedIds = old('religiones', $organizacion->religiones->pluck('id')->toArray());
                    @endphp
                    @foreach($religiones as $id => $nombre)
                    <option value="{{$id}}" {{ in_array($id, $selectedIds) ? 'selected' : '' }}>{{$nombre}}</option>
                    @endforeach

                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <label for="escudo" class="form-label mt-2">Escudo</label>
            <img alt="escudo" id="escudo-preview" src="{{asset("storage/escudos/" . $organizacion->escudo)}}" class="img-thumbnail" width="185" height="180">
            <input type="file" name="escudo" class="form-control form-control-sm @error('escudo') is-invalid @enderror" id="escudo">
            @error('escudo')
            <small class="invalid-feedback">{{$message}}</small>
            @enderror
          </div>
        </div>
      </div>
    </div> {{-- Fin sección de datos básicos y escudo --}}

    {{-- Campo de descripción breve --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_breve" label="Descripción breve" rows="2" :value="$organizacion->descripcion_breve" />
      </div>
    </div>
    {{-- Panel de pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="personajeTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="fisico-tab" data-toggle="pill" href="#tab-fisico" role="tab">Política y militar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="social-tab" data-toggle="pill" href="#tab-social" role="tab">Cultura y Sociedad</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="economia-tab" data-toggle="pill" href="#tab-economia" role="tab">Economía, tecnología y recursos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="historia-tab" data-toggle="pill" href="#tab-historia" role="tab">Historia y Otros</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="personajeTabContent">

          {{-- PESTAÑA 1: Geopolítica, militar, territorio y estructura --}}
          <div class="tab-pane fade show active" id="tab-fisico" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="geopolitica" label="Política exterior e interior" :value="$organizacion->geopolitica" />
                <x-textarea-input name="militar" label="Militar" :value="$organizacion->militar" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="territorio" label="Territorio y fronteras" :value="$organizacion->territorio" />
                <x-textarea-input name="estructura" label="Estructura organizativa" :value="$organizacion->estructura" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2: cultura, educación, religión --}}
          <div class="tab-pane fade" id="tab-social" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="educacion" label="Educación" :value="$organizacion->educacion" />
                <x-textarea-input name="religion" label="Religión" :value="$organizacion->religion" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="cultura" label="Aspectos culturales" :value="$organizacion->cultura" />
                <x-textarea-input name="demografia" label="Demografía" :value="$organizacion->demografia" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 3: Economía y tecnologia --}}
          <div class="tab-pane fade" id="tab-economia" role="tabpanel">
            <x-textarea-input name="tecnologia" label="Tecnología y ciencia" :value="$organizacion->tecnologia" />
            <x-textarea-input name="economia" label="Economía" :value="$organizacion->economia" />
            <x-textarea-input name="recursos_naturales" label="Recursos naturales" :value="$organizacion->recursos_naturales" />
          </div>

          {{-- PESTAÑA 4: Historia y otros --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="10" :value="$organizacion->historia" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" :value="$organizacion->otros" />
          </div>
        </div>
      </div>
    </div>{{-- Fin panel de pestañas --}}
  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 300
    })

    $('.summernote-lite').summernote({
      height: 150
    })

    //Preview de escudo antes de subirla
    document.getElementById('escudo').onchange = evt => {
      const [file] = document.getElementById('escudo').files
      if (file) {
        document.getElementById('escudo-preview').src = URL.createObjectURL(file)
      }
    }

    $('#religiones').select2({
      theme: 'bootstrap4', // Importante, pues la version bootstrap actual es la 4
      //placeholder: $('#religiones').data('placeholder'),
      allowClear: true,
      width: '100%',
      containerCssClass: ':all:'
    });

  });
</script>
@endsection