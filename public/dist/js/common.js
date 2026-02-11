$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Summernote
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