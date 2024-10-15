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

  //lleva el id a borrar al modal de confirmacion
  $(document).on('click', '.borrar', (e) => {
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');

    $('#id_borrar').val(id);
    $('#nombre-borrar').html(nombre);
    $('#nombre_borrado').val(nombre);
  });

  //lleva datos al modal editar enlace
  $(document).on('click', '.editar-enlace', (e) => {
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');
    const tipo = $(elemento).attr('tipo');
    const url = $(elemento).attr('url');

    $('#id_editar').val(id);
    $('#nombre-borrar').html(nombre);
    $('#nombre_editar').val(nombre);
    $('#url_editar').val(url);
    $('#tipo_editar').val(tipo);
  });

});