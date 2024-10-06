$(document).ready(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

	//lleva el id del articulo a borrar al modal de confirmacion
	$(document).on('click', '.borrar', (e)=>{
		//se usan 2 parentElement para llegar al tr desde el button #borrar en el que se hace click
		const elemento=$(this)[0].activeElement.parentElement.parentElement;
		const id=$(elemento).attr('artId');
		const nombre=$(elemento).attr('artNombre');
		
		$('#id_articulo').val(id);
		$('#nombre-articulo-borrar').html(nombre);
	});

  $('#form-borrar-articulo').submit(e=>{
		const id=$('#id_articulo').val();
    $.ajax({
      type: 'DELETE',
      url: id,
      data: {
        id: id,
      },
      success: function (response) {
        console.log(response);
        /*response es un JSON directamente desde el Controlador*/
        if(response.mensaje=='borrado'){
          toastr.success('Artículo eliminado.', 'Éxito');
          console.log(response.mensaje2);
        }else{
          toastr.error('No se pudo eliminar el artículo.', 'Error');
        }
        $('#form-borrar-articulo').trigger('reset');
      },
      error: function (jqXHR, textStatus, errorThrown) {
         alert('Ocurrió un error ' + jqXHR.responseText )
      }
    });
		/*$.post('../controlador/articulosController.php', { id, funcion}, (response)=>{
			if(response=='borrado'){
				toastr.success('Artículo eliminado.', 'Éxito');
			}
			if(response=='noborrado'){
				toastr.error('No se pudo eliminar el artículo.', 'Error');
			}
			$('#form-borrar-articulo').trigger('reset');
			buscar_articulos();
		})*/
		e.preventDefault();
	});

});