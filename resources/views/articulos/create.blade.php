@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('content')
<div class="row">
  <h1>Nuevo artículo</h1>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create" action="{{url('/articulos/articulos')}}" method="post">
    @csrf
    <div class="row mb-3 justify-content-center">
      <a type="button" class="btn btn-danger mr-1" id="cancelar" href="{{url('/articulos/index')}}">Cancelar</a>
      <button type="submit" class="btn btn-success ml-1" id="guardar">Guardar</button>
      <a class="btn btn-primary" type="button" id="volver-editar-button" href="cronicas.php" style="display:none">Volver</a>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline">
            <div class="card-header">
              <div class="row mb-2">
                <input id="id_editar" name="id_editar" type="hidden">
                <div class="col">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
                  @error('nombre')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
                <div class="col-4">
                  <label for="tipo" class="form-label">Tipo</label>
                  <select class="form-select form-control" name="tipo" id="tipo">
                    <option selected>Referencia</option>
                    <option>Canon</option>
                    <option>Crónica</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <label for="contenido" class="form-label">Contenido</label>
              <textarea class="form-control summernote" id="contenido" name="contenido" rows="8" aria-label="With textarea"></textarea>
                @error('contenido')
                <small style="color: red">{{$message}}</small>
                @enderror
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div><!-- /.col-->
      </div><!-- ./row -->
    </div> <!-- /container -->
  </form>
</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<!--<script src="../js/summernote-bs4.min.js"></script>
<script src="../js/articulos.js"></script>-->
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