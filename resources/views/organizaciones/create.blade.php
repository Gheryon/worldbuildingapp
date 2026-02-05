@extends('layouts.index')

@section('title')
<title id="title">Nueva organización</title>
@endsection

@section('navbar-buttons')
<li class="nav-item">
  <a href="{{route('organizaciones.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nueva organización</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-organization" class="position-relative needs-validation" action="{{route('organizacion.store')}}" method="post" enctype="multipart/form-data">
    @csrf
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
                <x-text-input name="nombre" label="Nombre" placeholder="Ej: La Compañía del Anillo, El Imperio Romano, etc." required />
              </div>
              <div class="col-md">
                <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Español, Narniano, etc." />
              </div>
              <div class="col-md">
                <x-text-input name="capital" label="Capital" placeholder="Ej: Minas Tirith, Córdoba, etc." />
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="select_tipo" class="form-label">Tipo de organización</label>
                <select class="form-select form-control" name="select_tipo" id="select_tipo" @if($tipo_organizacion->count()>0)required @endif>
                  <option selected disabled value="">Elegir</option>
                  @if($tipo_organizacion->count()>0)
                  @foreach($tipo_organizacion as $tipo)
                  <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
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
                  <option value="{{$id}}">{{$nombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md">
                <label for="select_organizacion_padre" class="form-label">Controlado por</label>
                <select class="form-select form-control" name="select_organizacion_padre" id="select_organizacion_padre">
                  <option selected disabled value="">Elegir</option>
                  @foreach($paises as $id => $nombre)
                  <option value="{{$id}}">{{$nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <x-date-input-group name="fundacion" label="Fecha de fundación"/>
              </div>
              <div class="col-md">
                <x-date-input-group name="disolucion" label="Fecha de disolución"/>
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <x-text-input name="lema" label="Lema" placeholder="Ej: Justicia para todos." />
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="religiones" class="form-label mt-2">Religiones presentes</label>
                  <select class="select2" multiple="multiple" name="religiones[]" id="religiones" data-placeholder="Selecciona religiones...">
                    @foreach($religiones as $id => $nombre)
                    <option value="{{$id}}" {{ (is_array(old('religiones')) && in_array($id, old('religiones'))) ? 'selected' : '' }}>{{$nombre}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <label for="escudo" class="form-label mt-2">Escudo</label>
            <img alt="escudo" id="escudo-preview" src="{{asset("storage/escudos/default.png")}}" class="img-thumbnail" width="185" height="180">
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
        <x-textarea-input name="descripcion_breve" label="Descripción breve" rows="2" />
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
                <x-textarea-input name="geopolitica" label="Política exterior e interior" />
                <x-textarea-input name="militar" label="Militar" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="territorio" label="Territorio y fronteras" />
                <x-textarea-input name="estructura" label="Estructura organizativa" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2: cultura, educación, religión --}}
          <div class="tab-pane fade" id="tab-social" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="educacion" label="Educación" />
                <x-textarea-input name="religion" label="Religión" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="cultura" label="Aspectos culturales" />
                <x-textarea-input name="demografia" label="Demografía" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 3: Economía y tecnologia --}}
          <div class="tab-pane fade" id="tab-economia" role="tabpanel">
            <x-textarea-input name="tecnologia" label="Tecnología y ciencia" />
            <x-textarea-input name="economia" label="Economía" />
            <x-textarea-input name="recursos_naturales" label="Recursos naturales" />
          </div>

          {{-- PESTAÑA 4: Historia y otros --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="10" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" />
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