@props(['imagenes' => null, 'entityType' => '', 'entityId' => null])

@section('specific-cases')
@parent
<link rel="preload" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css"></noscript>
@endsection

<div class="card card-dark card-outline mt-4">
  <div class="card-header">
    <h3 class="card-title">Imágenes de referencia</h3>
  </div>
  <div class="card-body">
    @if($imagenes && $imagenes->isNotEmpty())
    <div class="row mb-3" id="ref-images-container-{{ $entityId ?? 'create' }}">
      @foreach($imagenes as $imagen)
      <div class="col-md-3 mb-2 text-center" id="ref-image-{{ $imagen->id }}">
        <img src="{{ asset('storage/' . $imagen->path) }}" class="img-fluid rounded shadow-sm mb-1" style="height:150px;width:100%;object-fit:cover;">
        <button type="button" class="btn btn-danger btn-sm" title="Eliminar imagen"
          onclick="eliminarImagenReferencia('{{ route('imagenes.destroy-reference', [$entityType, $entityId, $imagen->id]) }}', {{ $imagen->id }})">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      @endforeach
    </div>
    @endif

    <div class="dropzone" id="dropzone-{{ $entityId ?? 'create' }}"></div>

    <input type="file" name="imagenes_referencia[]" id="file-input-{{ $entityId ?? 'create' }}" class="d-none" multiple accept="image/*">

    <small class="text-muted mt-2 d-block">Arrastra imágenes aquí o haz clic para seleccionar. Máx 5MB por imagen.</small>
  </div>
</div>

@section('specific-scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script>
Dropzone.autoDiscover = false;

(function() {
  var dropzoneId = 'dropzone-{{ $entityId ?? "create" }}';
  var inputId = 'file-input-{{ $entityId ?? "create" }}';
  var dropzoneEl = document.getElementById(dropzoneId);
  if (!dropzoneEl) return;
  var form = dropzoneEl.closest('form');
  if (!form) return;
  var fileInput = document.getElementById(inputId);
  if (!fileInput) return;

  var myDropzone = new Dropzone('#' + dropzoneId, {
    url: '#',
    autoProcessQueue: false,
    parallelUploads: 99,
    maxFilesize: 5,
    acceptedFiles: 'image/jpeg,image/png,image/jpg,image/gif,image.webp',
    addRemoveLinks: true,
    dictDefaultMessage: 'Arrastra imágenes aquí o haz clic para seleccionar',
    dictRemoveFile: 'Eliminar',
    dictFileTooBig: 'La imagen es demasiado grande (máx 5MB)',
    dictInvalidFileType: 'No se acepta este tipo de archivo.',
  });

  function sincronizarInput() {
    var dt = new DataTransfer();
    myDropzone.files.forEach(function(f) { dt.items.add(f); });
    fileInput.files = dt.files;
  }

  myDropzone.on('addedfile', sincronizarInput);
  myDropzone.on('removedfile', sincronizarInput);
  form.addEventListener('submit', function() {
    sincronizarInput();
  });
})();

function eliminarImagenReferencia(url, imagenId) {
  if (!confirm('¿Eliminar esta imagen?')) return;
  fetch(url, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Accept': 'application/json'
    }
  })
  .then(function(r) {
    if (r.ok) {
      var el = document.getElementById('ref-image-' + imagenId);
      if (el) el.remove();
      if (typeof toastr !== 'undefined') toastr.success('Imagen eliminada.');
    } else {
      if (typeof toastr !== 'undefined') toastr.error('No se pudo eliminar la imagen.');
    }
  })
  .catch(function() {
    if (typeof toastr !== 'undefined') toastr.error('No se pudo eliminar la imagen.');
  });
}
</script>
@endsection
