$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //lleva los datos al modal de editar en la vista de configuracion
  $(document).on('click', '.editar-tipo',(e)=>{
    const elemento=$(this)[0].activeElement;
    
    const nombre = elemento.getAttribute('data-nombre');
    const id = elemento.getAttribute('data-id');
    const tipo = elemento.getAttribute('data-tipo');
    
    $('#id_editar').val(id);
    $('#nombre_editar').val(nombre);
    $('#tipo_editar').val(tipo);
  });

  //lleva el id a borrar al modal de confirmacion en vista de configuracion
  $(document).on('click', '.borrar-tipo', (e) => {
    const elemento=$(this)[0].activeElement;
    const nombre = elemento.getAttribute('data-nombre');
    const id = elemento.getAttribute('data-id');
    const tipo = elemento.getAttribute('data-tipo');
    $('#id_borrar').val(id);
    $('#tipo').val(tipo);
    $('#texto-borrar').html(nombre);
  });


  //$('#nuevo-evento').on('show.bs.modal', function (event) {
  $(document).on('click', '.nuevo',(e)=>{
    $('#form-evento').trigger('reset');
    $('#descripcion').summernote('reset');
  });

  //lleva datos al modal editar enlace
  $(document).on('click', '.editar-enlace', (e) => {
    const elemento=$(this)[0].activeElement;

    const nombre = elemento.getAttribute('data-nombre');
    const id = elemento.getAttribute('data-id');
    const tipo = elemento.getAttribute('data-tipo');
    const url = elemento.getAttribute('data-url');

    $('#id_editar').val(id);
    $('#nombre-borrar').html(nombre);
    $('#nombre_editar').val(nombre);
    $('#url_editar').val(url);
    $('#tipo_editar').val(tipo);
  });
  
  //lleva los datos al modal de edicion en la vista index de nombres
  $(document).on('click', '.editar-nombres',(e)=>{
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');
    
    $('#id_editar').val(id);
    $('#nombres_editar').val(nombre);
  });

  //lleva el id a borrar al modal de confirmacion de las vistas index
  $(document).on('click', '.borrar', function(e) {
    const btn = $(this); 
    
    const nombre = btn.data('nombre'); // jQuery busca automáticamente 'data-nombre'
    const id = btn.data('id');

    // Referencias al modal
    const $modal = $('#eliminar-especie');

    // Llenamos los campos usando IDs únicos para evitar colisiones
    $modal.find('#id_borrar').val(id);
    $modal.find('#nombre-borrar').text(nombre); 
    $modal.find('#nombre_borrado').val(nombre);
    
    if(tipo) {
        $modal.find('#tipo').val(tipo);
    }
  });

  //evita el doble envio en el formulario de borrar
  $('#form-confirmar-borrar').on('submit', function() {
    const $btn = $(this).find('#confirmar-borrar-button');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');
});
});