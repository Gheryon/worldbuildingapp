$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /**
 * Previene la pérdida de datos en formularios no guardados.
 * @param {string} formId - El ID del formulario a monitorear.
 */
  function prevenirPerdidaDatos(formId) {
    let formChanged = false;
    const $form = $(formId);

    // Detectar cambios en inputs estándar
    $form.on('change keyup paste', 'input, select, textarea', function () {
      formChanged = true;
    });

    // Detectar cambios en el editor de summernote
    if ($('.summernote, .summernote-lite').length > 0) {
      $('.summernote, .summernote-lite').on('summernote.change', function () {
        formChanged = true;
      });
    }

    // Alerta antes de salir
    $(window).on('beforeunload', function () {
      if (formChanged) {
        return "Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?";
      }
    });

    // Desactivar alerta al enviar el formulario con éxito
    $form.on('submit', function () {
      $(window).off('beforeunload');
    });
  }

  //$(function () {
  $('form[data-prevent-loss="true"]').each(function () {
    prevenirPerdidaDatos('#' + $(this).attr('id'));
  });
  //});

  /** Inicialización de Summernote con configuración personalizada.
   * Detecta si es la versión Lite por la clase y ajusta la toolbar y altura.
   * También limpia el formato al pegar texto externo.
   */
  $('.summernote, .summernote-lite').each(function () {
    // Detectamos si es la versión lite por la clase o por un atributo
    const isLite = $(this).hasClass('summernote-lite');

    $(this).summernote({
      // Si tiene el atributo data-height usa ese, si no, usa el default según el tipo
      height: $(this).data('height') || (isLite ? 200 : 600),
      lang: 'es-ES',
      placeholder: $(this).attr('placeholder') || 'Escribe aquí...',
      toolbar: isLite ? [
        // Toolbar simplificado para versiones Lite
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['codeview']]
      ] : [
        // Toolbar completo para versiones grandes
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear', 'italic']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video', 'hr']],
        ['view', ['fullscreen', 'codeview']]
      ],
      // Callback para limpiar el pegado de texto externo
      callbacks: {
        onPaste: function (e) {
          var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
          e.preventDefault();
          setTimeout(function () {
            document.execCommand('insertText', false, bufferText);
          }, 10);
        }
      }
    });
  });

  /*=============Timelines===============*/
  $(document).on('click', '.editar', function () {
    const id = $(this).data('id');
    const urlEdit = window.AppConfig.routeEdit.replace(':id', id);
    const urlUpdate = window.AppConfig.routeUpdate.replace(':id', id);
    
    //Limpiar el formulario
    $('#form-evento')[0].reset();

    $.ajax({
      url: urlEdit,
      method: 'GET',
      success: function (data) {
        console.log(data);
        //Cambiar visualmente el modal
        $('#nuevoEventoLabel').text('Editar evento');
        $('#submit-crear-button').text('Actualizar cambios');

        //Cambiar el action y el método (Laravel necesita spoofing de PUT)
        $('#form-evento').attr('action', urlUpdate);
        if ($('#method_spoofing').length === 0) {
          $('#form-evento').append('<input type="hidden" name="_method" id="method_spoofing" value="PUT">');
        }

        //Poblar campos
        $('input[name="nombre"]').val(data.nombre);
        $('input[name="dia"]').val(data.dia);
        $('input[name="mes"]').val(data.mes);
        $('input[name="anno"]').val(data.anno);
        $('#form_tipo').val(data.tipo);
        $('#form_categoria').val(data.categoria);

        // 5. Cargar contenido en Summernote
        $('#descripcion').summernote('code', data.descripcion);

        $('#nuevo-evento').modal('show');
      }
    });
  });

  // IMPORTANTE: Limpiar el formulario cuando se cierre el modal para que "Nuevo Evento" vuelva a estar vacío
  $('#nuevo-evento').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#method_spoofing').remove();
    $('#form-evento').attr('action', "{{ route('evento.store') }}");
    $('#nuevoEventoLabel').text('Guardar');
    $('#descripcion').summernote('code', '');
  });
});