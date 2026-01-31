@extends('layouts.index')

@section('title')
<title id="title">Editar {{$religion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('religiones.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$religion->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-religion" class="position-relative needs-validation" action="{{route('religion.update', $religion->id)}}" method="post" enctype="multipart/form-data">
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
                <x-text-input name="nombre" label="Nombre" placeholder="Ej: Cristianismo, Judaísmo, etc." :value="$religion->nombre" required />
              </div>
              <div class="col-md">
                <x-text-input name="lema" label="Lema" placeholder="Ej: Justicia para todos." :value="$religion->lema" />
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <x-date-input-group name="fundacion" label="Fecha de fundación" :id="$religion->fundacion" :dia="$fundacion->dia ?? ''" :mes="$fundacion->mes ?? ''" :anno="$fundacion->anno ?? ''" />
              </div>
              <div class="col-md">
                <x-date-input-group name="disolucion" label="Fecha de disolución" :id="$religion->disolucion" :dia="$disolucion->dia ?? ''" :mes="$disolucion->mes ?? ''" :anno="$disolucion->anno ?? ''" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group mt-2">
                  <label for="tipo_teismo">Tipo de Teísmo</label>
                  <select name="tipo_teismo" id="tipo_teismo" class="form-control select2">
                    <option value="" selected disabled>Selecciona una doctrina...</option>
                    @foreach(\App\Models\Religion::getTiposTeismo() as $value => $label)
                    <option value="{{ $value }}"
                      {{ (old('tipo_teismo') == $value || (isset($religion) && $religion->tipo_teismo?->value == $value)) ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md">
                <x-text-input name="deidades" label="Deidades principales" placeholder="Ej: Zeus, Poseidón, Hades." :value="$religion->deidades" />
              </div>
              <div class="col-md">
                <div class="form-group mt-2">
                  <label for="estatus_legal" class="form-label">Estatus legal</label>
                  <select class="form-select form-control" name="estatus_legal" id="estatus_legal" required>
                    <option selected disabled value="">Elegir</option>
                    @foreach(['Activa', 'Clandestina', 'Extinta', 'Perseguida'] as $status)
                    <option value="{{ $status }}"
                      {{ old('estatus_legal', $religion->estatus_legal) == $status ? 'selected' : '' }}>
                      {{ $status }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <label for="escudo" class="form-label">Escudo</label>
            <img alt="escudo" id="escudo-preview" src="{{asset("storage/escudos/" . ($religion->escudo ?? "default.png"))}}" class="img-thumbnail" width="185" height="180">
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
        <x-textarea-input name="descripcion" label="Descripción" rows="2" :value="$religion->descripcion" />
      </div>
    </div>

    {{-- Panel de pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="personajeTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="fe-tab" data-toggle="pill" href="#tab-fe" role="tab">Elementos de fe</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="politica-tab" data-toggle="pill" href="#tab-politica" role="tab">Política</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="historia-tab" data-toggle="pill" href="#tab-historia" role="tab">Historia y otros</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="personajeTabContent">

          {{-- PESTAÑA 1: Geopolítica, militar, territorio y estructura --}}
          <div class="tab-pane fade show active" id="tab-fe" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="cosmologia" label="Cosmología" :value="$religion->cosmologia" />
                <x-textarea-input name="doctrina" label="Doctrina" :value="$religion->doctrina" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="sagrado" label="Lugares y objetos sagrados" :value="$religion->sagrado" />
                <x-textarea-input name="fiestas" label="Fiestas y rituales importantes" :value="$religion->fiestas" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2: política, estructuras, sectas --}}
          <div class="tab-pane fade" id="tab-politica" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="politica" label="Influencia politica" :value="$religion->politica" />
                <x-textarea-input name="sectas" label="Sectas" :value="$religion->sectas" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="estructura" label="Estructura religiosa" :value="$religion->estructura" />
                <x-textarea-input name="clase_sacerdotal" label="Clase sacerdotal" :value="$religion->clase_sacerdotal" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 3: Historia y otros --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="10" :value="$religion->historia" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" :value="$religion->otros" />
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
  });
</script>
@endsection