@extends('layouts.index')

@section('title')
<title id="title">Editar {{$cultura->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('culturas.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$cultura->nombre}}</h1>
  </div>
</div>
<hr>

<section class="content">
  <form id="form-edit-cultura" data-prevent-loss="true" class="position-relative needs-validation" action="{{route('culturas.update', $cultura->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
            <x-text-input name="nombre" label="Nombre" placeholder="Ej: Cultura Nórdica, Imperio Elfo, etc." :value="$cultura->nombre" required />
          </div>
          <div class="col-md">
            <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Nórdico, Élfico, etc." :value="$cultura->gentilicio" />
          </div>
          <div class="col-md">
            <div class="form-group mt-2">
              <label for="madre_id" class="form-label">Cultura madre</label>
              <select class="form-select form-control @error('madre_id') is-invalid @enderror" name="madre_id" id="madre_id">
                <option selected disabled value="">Elegir</option>
                <option value="">Ninguna</option>
                @foreach($culturas as $id => $nombre)
                <option value="{{$id}}" {{ old('madre_id', $cultura->madre_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
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
            <x-enum-select enum="App\Enums\TipoTerritorio" name="tipo_territorio" label="Territorio característico" sort :selected="old('tipo_territorio', $cultura->tipo_territorio?->value ?? '')" />
          </div>
          <div class="col-md">
            <div class="form-group mt-2">
              <label for="categoria" class="form-label">Importancia</label>
              <select class="form-select form-control @error('categoria') is-invalid @enderror" name="categoria" id="categoria">
                <option selected disabled value="">Elegir</option>
                <option value="local" {{ old('categoria', $cultura->categoria) == 'local' ? 'selected' : '' }}>Local</option>
                <option value="regional" {{ old('categoria', $cultura->categoria ?? 'regional') == 'regional' ? 'selected' : '' }}>Regional</option>
                <option value="global" {{ old('categoria', $cultura->categoria) == 'global' ? 'selected' : '' }}>Global</option>
              </select>
              @error('categoria')
              <small style="color: red">{{$message}}</small>
              @enderror
            </div>
          </div>
          <div class="col-md">
            <x-enum-select enum="App\Enums\ActitudMagia" name="actitud_magia" label="Actitud hacia la magia" :selected="old('actitud_magia', $cultura->actitud_magia?->value ?? '')" />
          </div>
          <div class="col-md">
            <x-enum-select enum="App\Enums\ActitudForasteros" name="actitud_forasteros" label="Actitud hacia los forasteros" :selected="old('actitud_forasteros', $cultura->actitud_forasteros?->value ?? '')" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <x-enum-select enum="App\Enums\EstatusCultura" name="estatus" label="Estatus" :selected="old('estatus', $cultura->estatus?->value ?? '')" />
          </div>
          <div class="col-md">
            <x-date-input-group name="fundacion" label="Fecha de fundación" :id="$cultura->fundacion_id" :dia="$cultura->fecha_fundacion->dia ?? ''" :mes="$cultura->fecha_fundacion->mes ?? ''" :anno="$cultura->fecha_fundacion->anno ?? ''"/>
          </div>
          <div class="col-md">
            <x-date-input-group name="disolucion" label="Fecha de disolución" :id="$cultura->disolucion_id" :dia="$cultura->fecha_disolucion->dia ?? ''" :mes="$cultura->fecha_disolucion->mes ?? ''" :anno="$cultura->fecha_disolucion->anno ?? ''" />
          </div>
        </div>
      </div>
    </div>

    {{-- Campo de descripción breve --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_breve" label="Descripción breve" rows="2" :value="$cultura->descripcion_breve" />
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
            <x-textarea-input name="distribucion_geografica" label="Distribución geográfica" rows="6" :value="$cultura->distribucion_geografica" />
          </div>

          {{-- PESTAÑA 2: Lengua y sociedad --}}
          <div class="tab-pane fade" id="tab-lengua-sociedad" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="idioma" label="Idioma y escritura" :value="$cultura->idioma" />
                <x-textarea-input name="estructura_social" label="Estructura social" :value="$cultura->estructura_social" />
              </div>
              <div class="col-md-6">
                <div class="form-group mt-2">
                  <label for="unidad_familiar" class="form-label">Unidad familiar</label>
                  <select class="form-select form-control @error('unidad_familiar') is-invalid @enderror" name="unidad_familiar" id="unidad_familiar">
                    <option selected disabled value="">Elegir</option>
                    <option value="nuclear" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'nuclear' ? 'selected' : '' }}>Nuclear</option>
                    <option value="extensa" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'extensa' ? 'selected' : '' }}>Extensa</option>
                    <option value="clan" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'clan' ? 'selected' : '' }}>Clan</option>
                    <option value="tribu" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'tribu' ? 'selected' : '' }}>Tribu</option>
                    <option value="linaje" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'linaje' ? 'selected' : '' }}>Linaje</option>
                    <option value="comunidad" {{ old('unidad_familiar', $cultura->unidad_familiar) == 'comunidad' ? 'selected' : '' }}>Comunidad</option>
                  </select>
                  @error('unidad_familiar')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
                <x-textarea-input name="roles_genero" label="Roles de género" :value="$cultura->roles_genero" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 3: Cultura material --}}
          <div class="tab-pane fade" id="tab-cultura-material" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="vestimenta" label="Vestimenta tradicional" :value="$cultura->vestimenta" />
                <x-textarea-input name="gastronomia" label="Gastronomía" :value="$cultura->gastronomia" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="arquitectura" label="Arquitectura" :value="$cultura->arquitectura" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 4: Cosmovisión --}}
          <div class="tab-pane fade" id="tab-cosmovision" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="cosmovision" label="Cosmovisión" :value="$cultura->cosmovision" />
                <x-textarea-input name="fiestas" label="Fiestas y festivales" :value="$cultura->fiestas" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="tabues" label="Tabúes" :value="$cultura->tabues" />
                <x-textarea-input name="simbolos" label="Símbolos culturales" :value="$cultura->simbolos" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 5: Ética y artes --}}
          <div class="tab-pane fade" id="tab-etica-artes" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="etica" label="Ética y código moral" :value="$cultura->etica" />
                <x-textarea-input name="arte_musica" label="Arte y música" :value="$cultura->arte_musica" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="tecnologia" label="Tecnología" :value="$cultura->tecnologia" />
                <x-textarea-input name="educacion" label="Educación" :value="$cultura->educacion" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 6: Historia --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="10" :value="$cultura->historia" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" :value="$cultura->otros" />
          </div>
        </div>
      </div>
    </div>

    <x-reference-images-manager :imagenes="$cultura->imagenes" entityType="culturas" :entityId="$cultura->id" />
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
