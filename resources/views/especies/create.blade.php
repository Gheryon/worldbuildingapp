@extends('layouts.index')

@section('title')
<title id="title">Nueva especie</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('especies.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nueva especie</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-especie" class="position-relative needs-validation" action="{{route('especie.store')}}" method="post" enctype="multipart/form-data">
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
          <div class="col-md">
            <x-text-input name="nombre" label="Nombre" placeholder="Ej: Perro, dragón, etc." />
          </div>
          <div class="col-md">
            <label for="reino" class="form-label mt-2">Reino</label>
            <select class="form-select form-control" name="reino" id="reino" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Animalia', 'Fungi', 'Monera', 'Plantae', 'Protista'] as $reino)
              <option value="{{ $reino }}"
                {{ old('reino') == $reino ? 'selected' : '' }}>
                {{ $reino }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="clase_taxonomica" class="form-label mt-2">Clase taxonómica</label>
            <select class="form-select form-control" name="clase_taxonomica" id="clase_taxonomica" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Anfibio', 'Arácnidos', 'Ave', 'Insectos', 'Mamífero', 'Reptil', 'Peces'] as $clase)
              <option value="{{ $clase }}"
                {{ old('clase_taxonomica') == $clase ? 'selected' : '' }}>
                {{ $clase }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="locomocion" class="form-label mt-2">Locomoción</label>
            <select class="form-select form-control" name="locomocion" id="locomocion" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Acuático', 'Caminante', 'Escalador', 'Mixto', 'Terrestre', 'Volador'] as $locomocion)
              <option value="{{ $locomocion }}"
                {{ old('locomocion') == $locomocion ? 'selected' : '' }}>
                {{ $locomocion }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="organizacion_social" class="form-label mt-2">Organización social</label>
            <select class="form-select form-control" name="organizacion_social" id="organizacion_social" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Clan familiar', 'Colonia', 'Manada', 'Rebaño', 'Solitaria'] as $organizacion)
              <option value="{{ $organizacion }}"
                {{ old('organizacion_social') == $organizacion ? 'selected' : '' }}>
                {{ $organizacion }}
              </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <x-text-input name="edad" label="Esperanza de vida media" placeholder="Ej: 10 años, 50 años, etc." />
          </div>
          <div class="col-md">
            <x-text-input name="mortalidad" label="Mortalidad" placeholder="Ej: 10%, 50%, etc." />
          </div>
          <div class="col-md">
            <x-text-input name="peso" label="Peso" placeholder="Ej: 5kg, 300kg, etc." />
          </div>
          <div class="col-md">
            <x-text-input name="altura" label="Altura" placeholder="Ej: 2m, 10cm, etc." />
          </div>
          <div class="col-md">
            <x-text-input name="longitud" label="Longitud" placeholder="Ej: 3m, 1cm, etc." />
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <label for="dieta" class="form-label mt-2">Dieta</label>
            <select class="form-select form-control" name="dieta" id="dieta" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Carnívoro', 'Herbívoro', 'Insectívoro', 'Omnívoro'] as $dieta)
              <option value="{{ $dieta }}"
                {{ old('dieta') == $dieta ? 'selected' : '' }}>
                {{ $dieta }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="rareza" class="form-label mt-2">Rareza</label>
            <select class="form-select form-control" name="rareza" id="rareza" required>
              <option selected disabled value="">Elegir</option>
              @foreach(['Común', 'Legendario', 'Mítológico', 'Raro'] as $rareza)
              <option value="{{ $rareza }}"
                {{ old('rareza') == $rareza ? 'selected' : '' }}>
                {{ $rareza }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="estatus" class="form-label mt-2">Estatus</label>
            <select class="form-select form-control" name="estatus" id="estatus" required>
              <option selected disabled value="">Elegir</option>
              <option value="Viva">Viva</option>
              <option value="En peligro">En peligro</option>
              <option value="Extinta">Extinta</option>
            </select>
          </div>
        </div>
      </div>
    </div>{{-- Fin sección de datos básicos y escudo --}}

    {{-- Panel de pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="personajeTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="fisico-tab" data-toggle="pill" href="#tab-fisico" role="tab">Descripción física</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="ecologia-tab" data-toggle="pill" href="#tab-ecologia" role="tab">Ecología y habilidades</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="otros-tab" data-toggle="pill" href="#tab-otros" role="tab">Otros aspectos</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="personajeTabContent">

          {{-- PESTAÑA 1: Descripción física --}}
          <div class="tab-pane fade show active" id="tab-fisico" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="anatomia" label="Anatomía" />
                <x-textarea-input name="alimentacion" label="Alimentación" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="reproduccion" label="Reproducción y crecimiento" />
                <x-textarea-input name="dimorfismo_sexual" label="Dimorfismo sexual" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2: Ecología y habilidades --}}
          <div class="tab-pane fade" id="tab-ecologia" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="distribucion" label="Distribución y hábitats" />
                <x-textarea-input name="habilidades" label="Habilidades y sentidos especiales" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="domesticacion" label="Domesticación" />
                <x-textarea-input name="explotacion" label="Explotación" />
              </div>
            </div>
          </div>
          {{-- PESTAÑA 3: Otros --}}
          <div class="tab-pane fade" id="tab-otros" role="tabpanel">
            <x-textarea-input name="otros" label="Otros" />
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
  });
</script>
@endsection