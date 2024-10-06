$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Summernote
  $('.summernote').summernote({
    height: 300,
    //callbacks: {
      //onImageUpload: function (files) {
        //sendFile(files[0]);
        //console.log("sendFile()");
      //}
    //}
  });

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
      success: function (url) {
        $('.summernote').summernote("insertImage", url, 'filename');
      },
      error: function (data) {
        console.log(data);
      }
    });
  }
});