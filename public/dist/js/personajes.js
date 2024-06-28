$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //lleva el id del personaje a borrar al modal de confirmacion
  $(document).on('click', '.borrar', (e) => {
    const elemento=$(this)[0].activeElement;
    let id=$(elemento).attr('id');
    const nombre = $(elemento).attr('nombre');

    $('#id_personaje').val(id);
    $('#nombre-personaje-borrar').html(nombre);
  });

});