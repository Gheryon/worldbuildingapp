$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //$('#nuevo-evento').on('show.bs.modal', function (event) {
  $(document).on('click', '.nuevo',(e)=>{
    $('#form-evento').trigger('reset');
    $('#descripcion').summernote('reset');
  });

  //llena el formulario para editar un evento
  $(document).on('click', '.editar', (e) => {
    $('#form-evento').trigger('reset');
    $('#descripcion').summernote('reset');
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    $('#id_editar').val(id);
    $.ajax({
      type: 'GET',
      url: id+"/edit",
      data: {
        id: id,
      },
      success: function (response) {
        /*response es un JSON directamente desde el Controlador*/
        $('#nombre').val(response.evento.nombre);
        if(response.evento.dia!=0){
          $('#dia').val(response.evento.dia);
        }
        if(response.evento.mes!=0){
          $('#mes').val(response.evento.mes);
        }
        $('#anno').val(response.evento.anno);
        $('#select_timeline').val(response.evento.id_linea_temporal);
        $('#descripcion').summernote('code',response.evento.descripcion);
        
        //console.log(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        //alert('Ocurrió un error ' + jqXHR.responseText )
        console.log('Ocurrió un error ' + jqXHR.responseText );
      }
    });
  });

  //lleva el id del evento a borrar al modal de confirmacion
  $(document).on('click', '.borrar', (e) => {
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');
    console.log(id);
    $('#id_evento').val(id);
    $('#nombre-evento-borrar').html(nombre);
  });

});