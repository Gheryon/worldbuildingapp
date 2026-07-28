@extends('layouts.index')

@section('title')
<title id="title">Nueva cultura</title>
@endsection

@section('navbar-buttons')
<li class="nav-item">
  <a href="{{route('culturas.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nueva cultura</h1>
  </div>
</div>
<hr>

<section class="content">
  <form id="form-create-cultura" data-prevent-loss="true" class="position-relative needs-validation" action="{{route('culturas.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success px-5 shadow-sm">Guardar</button>
      </div>
    </div>

    {{-- Sección de datos básicos --}}
    <div class="card card-outline card-dark mt-3">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-hammer mr-1"></i> Información básica</h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md">
            <x-text-input name="nombre" label="Nombre" placeholder="Ej: Cultura Nórdica, Imperio Elfo, etc." required />
          </div>
          <div class="col-md">
            <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Nórdico, Élfico, etc." />
          </div>
          <div class="col-md">
            <div class="form-group mt-2">
              <label for="madre_id" class="form-label">Cultura madre</label>
              <select class="form-select form-control @error('madre_id') is-invalid @enderror" name="madre_id" id="madre_id">
                <option selected disabled value="">Elegir</option>
                <option value="">Ninguna</option>
                @foreach($culturas as $id => $nombre)
                <option value="{{$id}}">{{$nombre}}</option>
                @endforeach
              </select>
              @error('madre_id')
              <small style="color: red">{{$message}}</small>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <x-enum-select enum="App\Enums\TipoTerritorio" name="tipo_territorio" label="Territorio característico" sort />
          </div>
          <div class="col-md">
            <div class="form-group mt-2">
              <label for="categoria" class="form-label">Importancia</label>
              <select class="form-select form-control @error('categoria') is-invalid @enderror" name="categoria" id="categoria">
                <option selected disabled value="">Elegir</option>
                    <option value="local">Local</option>
                    <option value="regional">Regional</option>
                    <option value="continental">Continental</option>
                    <option value="global">Global</option>
                    <option value="universal">Universal</option>
              </select>
              @error('categoria')
              <small style="color: red">{{$message}}</small>
              @enderror
            </div>
          </div>
          <div class="col-md">
            <x-enum-select enum="App\Enums\ActitudMagia" name="actitud_magia" label="Actitud hacia la magia" />
          </div>
          <div class="col-md">
            <x-enum-select enum="App\Enums\ActitudForasteros" name="actitud_forasteros" label="Actitud hacia los forasteros" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <x-enum-select enum="App\Enums\EstatusCultura" name="estatus" label="Estatus" />
          </div>
          <div class="col-md">
            <x-date-input-group name="fundacion" label="Fecha de origen"/>
          </div>
          <div class="col-md">
            <x-date-input-group name="disolucion" label="Fecha de desaparición"/>
          </div>
        </div>
      </div>
    </div>

    {{-- Campo de descripción breve --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_breve" label="Descripción breve" rows="2" />
      </div>
    </div>

    {{-- Panel de pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="culturaTab" role="tablist">
          <li class="nav-item"><a class="nav-link active" id="geografia-tab" data-toggle="pill" href="#tab-geografia" role="tab"><i class="fas fa-map mr-1"></i> Geografía</a></li>
          <li class="nav-item"><a class="nav-link" id="lengua-sociedad-tab" data-toggle="pill" href="#tab-lengua-sociedad" role="tab"><i class="fas fa-people-group mr-1"></i> Lengua y sociedad</a></li>
          <li class="nav-item"><a class="nav-link" id="cultura-material-tab" data-toggle="pill" href="#tab-cultura-material" role="tab"><i class="fas fa-utensils mr-1"></i> Cultura material</a></li>
          <li class="nav-item"><a class="nav-link" id="cosmovision-tab" data-toggle="pill" href="#tab-cosmovision" role="tab"><i class="fas fa-eye mr-1"></i> Cosmovisión</a></li>
          <li class="nav-item"><a class="nav-link" id="etica-artes-tab" data-toggle="pill" href="#tab-etica-artes" role="tab"><i class="fas fa-palette mr-1"></i> Ética y artes</a></li>
          <li class="nav-item"><a class="nav-link" id="historia-tab" data-toggle="pill" href="#tab-historia" role="tab"><i class="fas fa-scroll mr-1"></i> Historia</a></li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="culturaTabContent">

          {{-- PESTAÑA 1: Geografía --}}
          <div class="tab-pane fade show active" id="tab-geografia" role="tabpanel">
            <x-textarea-input name="distribucion_geografica" label="Distribución geográfica" rows="6" />
          </div>

          {{-- PESTAÑA 2: Lengua y sociedad --}}
          <div class="tab-pane fade" id="tab-lengua-sociedad" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="idioma" label="Idioma y escritura" />
                <x-textarea-input name="estructura_social" label="Estructura social" />
              </div>
              <div class="col-md-6">
                <div class="form-group mt-2">
                  <label for="unidad_familiar" class="form-label">Unidad familiar</label>
                  <select class="form-select form-control @error('unidad_familiar') is-invalid @enderror" name="unidad_familiar" id="unidad_familiar">
                    <option selected disabled value="">Elegir</option>
                    <option value="nuclear">Nuclear</option>
                    <option value="extensa">Extensa</option>
                    <option value="clan">Clan</option>
                    <option value="tribu">Tribu</option>
                    <option value="linaje">Linaje</option>
                    <option value="comunidad">Comunidad</option>
                  </select>
                  @error('unidad_familiar')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
                <x-textarea-input name="roles_genero" label="Roles de género" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 3: Cultura material --}}
          <div class="tab-pane fade" id="tab-cultura-material" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="vestimenta" label="Vestimenta tradicional" />
                <x-textarea-input name="gastronomia" label="Gastronomía" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="arquitectura" label="Arquitectura" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 4: Cosmovisión --}}
          <div class="tab-pane fade" id="tab-cosmovision" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="cosmovision" label="Cosmovisión"/>
                <x-textarea-input name="fiestas" label="Fiestas y festivales" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="tabues" label="Tabúes" />
                <x-textarea-input name="simbolos" label="Símbolos culturales" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 5: Ética y artes --}}
          <div class="tab-pane fade" id="tab-etica-artes" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="etica" label="Ética y código moral" />
                <x-textarea-input name="arte_musica" label="Arte y música" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="tecnologia" label="Tecnología" />
                <x-textarea-input name="educacion" label="Educación" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 6: Historia --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="10" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" />
          </div>
        </div>
      </div>
    </div>

    <x-reference-images-manager />
    
  </form>
</section>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    $('#madre_id').select2({
      theme: 'bootstrap4',
      allowClear: true,
      width: '100%',
      containerCssClass: ':all:'
    });
  });
</script>
@endsection
