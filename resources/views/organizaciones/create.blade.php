@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nueva organización</title>
@endsection

@section('navbar-buttons')
<a href="{{route('organizaciones.index')}}" class="btn btn-dark">Cancelar</a>
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
        <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
        <a class="btn btn-primary" type="button" id="volver-crear-button" href="{{route('organizaciones.index')}}" style="display:none">Volver</a>
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
            <label for="gentilicio" class="form-label">Gentilicio</label>
            <input type="text" name="gentilicio" class="form-control" id="gentilicio" placeholder="Nombre de los habitantes">
          </div>
          <div class="col-md">
            <label for="capital" class="form-label">Capital</label>
            <input type="text" name="capital" class="form-control" id="capital">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-3">
            <label for="select_tipo" class="form-label">Tipo de organización</label>
            <select class="form-select form-control" name="select_tipo" id="select_tipo" required>
              <option selected disabled value="">Elegir</option>
                @foreach($tipo_organizacion as $tipo)
                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="soberano" class="form-label">Soberano</label>
            <select class="form-select form-control" name="soberano" id="soberano" >
              <option selected disabled value="">Elegir</option>
              @foreach($personajes as $personaje)
              <option value="{{$personaje->id}}">{{$personaje->Nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="owner" class="form-label">Controlado por</label>
            <select class="form-select form-control" name="owner" id="owner" >
              <option selected disabled value="">Elegir</option>
              @foreach($paises as $pais)
              <option value="{{$pais->id_organizacion}}">{{$pais->nombre}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md">
            <label for="id_fundacion" class="form-label">Fecha de fundación</label>
            <div class="input-group">
              <input id="id_fundacion" type="hidden" name="id_fundacion" value="0">
              <input type="number" id="dfundacion" name="dfundacion" class="form-select form-control" placeholder="Día">
              <select id="mfundacion" name="mfundacion" class="form-control">
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
              <input type="number" id="afundacion" name="afundacion" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col-md">
              <label for="id_disolucion" class="form-label">Fecha de disolución</label>
            <div class="input-group">
              <input id="id_disolucion" type="hidden" name="id_disolucion" value="0">
              <input type="number" id="ddisolucion" name="ddisolucion" class="form-control" placeholder="Día">
              <select id="mdisolucion" name="mdisolucion" class="form-select form-control" placeholder="Mes">
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
              <input type="number" id="adisolucion" name="adisolucion" class="form-control" placeholder="Año">
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md">
            <label for="lema" class="form-label">Lema</label>
            <input type="text" name="lema" class="form-control" id="lema">
          </div>
        </div>
      </div>
      <div class="col-md-3 mt-2 mb-2">
        <label for="escudo" class="form-label">Escudo</label>
        <img alt="escudo" id="escudo-img" src="{{asset("storage/escudos/default.png")}}" class="img-fluid" width="185" height="180">
        <input type="file" name="escudo" class="form-control" id="escudo">
      </div>
    </div>

    <div class="row mt-2 mb-3">
      <div class="col">
      <label for="DescripcionShort" class="form-label">Descripción breve</label>
      <textarea name="DescripcionShort" class="form-control summernote-lite" id="DescripcionShort" rows="2" aria-label="With textarea"></textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote-lite" id="historia" rows="4" aria-label="With textarea"></textarea>

    <label for="politica" class="form-label">Política exterior e interior</label>
    <textarea name="politica" class="form-control summernote-lite" id="politica" rows="4" aria-label="With textarea"></textarea>
    
    <label for="militar" class="form-label">Militar</label>
    <textarea name="militar" class="form-control summernote-lite" id="militar" rows="4" aria-label="With textarea"></textarea>
    
    <label for="estructura" class="form-label">Estructura organizativa</label>
    <textarea name="estructura" class="form-control summernote-lite" id="estructura" rows="4" aria-label="With textarea"></textarea>
    
    <label for="territorio" class="form-label">Territorio</label>
    <textarea name="territorio" class="form-control summernote-lite" id="territorio" rows="4" aria-label="With textarea"></textarea>
    
    <label for="frontera" class="form-label">Fronteras</label>
    <textarea name="frontera" class="form-control summernote-lite" id="frontera" rows="4" aria-label="With textarea"></textarea>
    
    <label for="religion" class="form-label">Religión</label>
    <textarea name="religion" class="form-control summernote-lite" id="religion" rows="4" aria-label="With textarea"></textarea>
    
    <label for="demografia" class="form-label">Demografía</label>
    <textarea name="demografia" class="form-control summernote-lite" id="demografia" rows="4" aria-label="With textarea"></textarea>
    
    <label for="cultura" class="form-label">Aspectos culturales</label>
    <textarea name="cultura" class="form-control summernote-lite" id="cultura" rows="4" aria-label="With textarea"></textarea>
    
    <label for="educacion" class="form-label">Educación</label>
    <textarea name="educacion" class="form-control summernote" id="educacion" rows="4" aria-label="With textarea"></textarea>

    <label for="tecnologia" class="form-label">Tecnología y ciencia</label>
    <textarea name="tecnologia" class="form-control summernote" id="tecnologia" rows="4" aria-label="With textarea"></textarea>
    
    <label for="economia" class="form-label">Economía</label>
    <textarea name="economia" class="form-control summernote" id="economia" rows="4" aria-label="With textarea"></textarea>

    <label for="recursos" class="form-label">Recursos naturales</label>
    <textarea name="recursos" class="form-control summernote" id="recursos" rows="4" aria-label="With textarea"></textarea>

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