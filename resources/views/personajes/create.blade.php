@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nuevo personaje</title>
@endsection

@section('navbar-buttons')
<a href="{{route('personajes.index')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nuevo personaje</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-personaje" class="position-relative needs-validation" action="{{url('/personajes/store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
        <a class="btn btn-primary" type="button" id="volver-crear-button" href="{{route('personajes.index')}}" style="display:none">Volver</a>
      </div>
    </div>
    <div class="row mt-3 mb-3 justify-content-md-center border">
      <div class="col">
        <div class="row mt-2">
          <div class="col-md">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="nombre_familia" class="form-label">Nombre de familia</label>
            <input type="text" name="nombre_familia" class="form-control" id="nombre_familia" placeholder="Nombre de la familia o clan">
          </div>
          <div class="col-md">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-3">
            <label for="sexo" class="form-label">Sexo</label>
            <select class="form-select form-control" name="sexo" id="sexo" required>
              <option selected disabled value="">Elegir</option>
              <option>Hombre</option>
              <option>Mujer</option>
            </select>
          </div>
          <div class="col-md-3">
            @if (Arr::has($especies, 'error.error'))
              {{Arr::get($especies, 'error.error')}}
            @else
              <label for="select_especie" class="form-label">Especie</label>
              <select class="form-select form-control" name="select_especie" id="select_especie" required>
                <option selected disabled value="">Elegir</option>
                  @foreach($especies as $especie)
                  <option value="{{$especie->id}}">{{$especie->nombre}}</option>
                  @endforeach
              </select>
              @endif
          </div>
          <div class="col-md">
            <label for="lugar_nacimiento" class="form-label">Lugar de nacimiento</label>
            <input type="text" name="lugar_nacimiento" class="form-control" id="lugar_nacimiento" placeholder="Lugar de nacimiento">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-4">
            <label for="id_nacimiento" class="form-label">Fecha de nacimiento</label>
            <div class="input-group">
              <input id="id_nacimiento" type="hidden" name="id_nacimiento" value="0">
              <input type="number" id="dnacimiento" name="dnacimiento" class="form-select form-control" placeholder="Día">
              <select id="mnacimiento" name="mnacimiento" class="form-control">
                <option selected disabled value="">Mes</option>
                <option value="0">Semana de año nuevo</option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
              <input type="number" id="anacimiento" name="anacimiento" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col-md-4">
              <label for="id_fallecimiento" class="form-label">Fecha de fallecimiento</label>
            <div class="input-group">
              <input id="id_fallecimiento" type="hidden" name="id_fallecimiento" value="0">
              <input type="number" id="dfallecimiento" name="dfallecimiento" class="form-control" placeholder="Día">
              <select id="mfallecimiento" name="mfallecimiento" class="form-select form-control" placeholder="Mes">
                <option selected disabled value="">Mes</option>
                <option value="0">Semana de año nuevo</option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
              <input type="number" id="afallecimiento" name="afallecimiento" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col-md">
            <label for="causa_fallecimiento" class="form-label">Causa de fallecimiento</label>
            <input type="text" name="causa_fallecimiento" class="form-control" id="causa_fallecimiento" placeholder="Causa de fallecimiento">
          </div>
        </div>
      </div>
      <div class="col-md-3 mt-2 mb-2">
        <label for="retrato" class="form-label">Retrato</label>
        <img alt="retrato" id="retrato-img" src="{{asset('storage/retratos/default.png')}}" class="img-fluid" width="185" height="180">
        <input type="file" name="retrato" class="form-control" id="retrato">
      </div>
    </div>
    <div class="row mt-2 mb-3">
      <div class="col">
      <label for="DescripcionShort" class="form-label">Descripción breve</label>
      <textarea name="DescripcionShort" class="form-control summernote-lite" id="DescripcionShort" rows="2" aria-label="With textarea"></textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="descripcion" class="form-label">Descripción física</label>
    <textarea name="descripcion" class="form-control summernote-lite" id="descripcion" rows="4" aria-label="With textarea"></textarea>

    <label for="personalidad" class="form-label">Personalidad</label>
    <textarea name="personalidad" class="form-control summernote-lite" id="personalidad" rows="4" aria-label="With textarea"></textarea>
    
    <label for="deseos" class="form-label">Principales deseos</label>
    <textarea name="deseos" class="form-control summernote-lite" id="deseos" rows="4" aria-label="With textarea"></textarea>
    
    <label for="miedos" class="form-label">Principales miedos</label>
    <textarea name="miedos" class="form-control summernote-lite" id="miedos" rows="4" aria-label="With textarea"></textarea>
    
    <label for="magia" class="form-label">Habilidades Mágicas</label>
    <textarea name="magia" class="form-control summernote-lite" id="magia" rows="4" aria-label="With textarea"></textarea>
    <!----------------------------------------------->
    <label for="educacion" class="form-label">Educación y cultura</label>
    <textarea name="educacion" class="form-control summernote-lite" id="educacion" rows="4" aria-label="With textarea"></textarea>
    
    <label for="religion" class="form-label">Religión</label>
    <textarea name="religion" class="form-control summernote-lite" id="religion" rows="4" aria-label="With textarea"></textarea>
    
    <label for="familia" class="form-label">Familia y riqueza</label>
    <textarea name="familia" class="form-control summernote-lite" id="familia" rows="4" aria-label="With textarea"></textarea>
    
    <label for="politica" class="form-label">Política y títulos</label>
    <textarea name="politica" class="form-control summernote-lite" id="politica" rows="4" aria-label="With textarea"></textarea>
    <!----------------------------------------------->
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote" id="historia" rows="8" aria-label="With textarea"></textarea>
    
    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote-lite" id="otros" rows="4" aria-label="With textarea"></textarea>
  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 300,
      callbacks: {
        onImageUpload: function(files) {
          sendFile(files[0]);
        }
      }
    })

    $('.summernote-lite').summernote({
      height: 150,
      callbacks: {
        onImageUpload: function(files) {
          sendFile(files[0]);
        }
      }
    })
    function sendFile(file) {
      //var url = '{{ route("articulos.get", ":id") }}';
      //url = url.replace(':id', id);

      data = new FormData();
      data.append("file", file);
      $.ajax({
        data: data,
        type: "POST",
        url: "../controlador/imagenesController.php",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
          $('.summernote').summernote("insertImage", url, 'filename');
        },
        error: function(data) {
          console.log(data);
        }
      });
    }
  });
</script>
@endsection