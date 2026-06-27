@props(['imagenes', 'entityId'])

@if($imagenes && $imagenes->isNotEmpty())
<section class="mb-5">
  <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
    <i class="fas fa-images mr-2 opacity-75"></i>Imágenes de referencia
  </h2>
  <div class="row">
    @foreach($imagenes as $imagen)
    <div class="col-md-4 mb-3">
      <img src="{{ asset('storage/' . $imagen->path) }}" 
           class="img-fluid rounded shadow-sm img-thumbnail-trigger" 
           style="height:200px;width:100%;object-fit:cover;cursor:pointer;"
           data-toggle="modal" 
           data-target="#imageModal"
           data-src="{{ asset('storage/' . $imagen->path) }}"
           data-title="Imagen de referencia">
    </div>
    @endforeach
  </div>
</section>

<x-image-modal />
@endif
