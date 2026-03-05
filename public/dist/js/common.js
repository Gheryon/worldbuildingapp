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
        ['insert', ['link']],
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
});