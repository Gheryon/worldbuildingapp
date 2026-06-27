<!-- Modal imagen completa -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" id="imageModalSource" alt="Imagen ampliada" style="width:100%;">
      </div>
    </div>
  </div>
</div>

@section('specific-scripts')
@parent
<script>
  $(function() {
    // Manejar apertura de modal para ver imagen
    $('.img-thumbnail-trigger').off('click').on('click', function() {
      var src = $(this).data('src');
      var title = $(this).data('title');

      $('#imageModalTitle').text(title);
      $('#imageModalSource').attr('src', src);
    });
  });
</script>
@endsection