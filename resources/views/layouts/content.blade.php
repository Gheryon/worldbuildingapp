@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Vista</title>
@endsection

@section('content')
<h1>Vista contenido</h1>

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md">
        <div class="row contentApp" id="content-left">
          {{$vista}}
          <?php var_dump($left);?>
        @foreach($left as $personaje)
          {{$personaje}}
        @endforeach
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection