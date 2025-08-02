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
});