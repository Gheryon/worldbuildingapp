$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  
  $(document).on('click', '.editar-tipo',(e)=>{
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');
    const tipo = $(elemento).attr('tipo');
    
    $('#id_editar').val(id);
    $('#nombre_editar').val(nombre);
    $('#tipo_editar').val(tipo);
  });

  //lleva el id a borrar al modal de confirmacion
  $(document).on('click', '.borrar-tipo', (e) => {
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');
    const tipo = $(elemento).attr('tipo');

    $('#id_borrar').val(id);
    $('#tipo').val(tipo);
    $('#nombre_borrado').val(nombre);
    $('#texto-borrar').html(nombre);
  });


  //$('#nuevo-evento').on('show.bs.modal', function (event) {
  $(document).on('click', '.nuevo',(e)=>{
    $('#form-evento').trigger('reset');
    $('#descripcion').summernote('reset');
  });

});