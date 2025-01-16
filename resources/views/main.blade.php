@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Worldbuilding app</title>
@endsection

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Instituciones</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-building-columns"></i>
          </div>
          <a href="{{route('organizaciones.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Religiones</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-place-of-worship"></i>
          </div>
          <a href="{{route('religiones.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Personajes</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-people-group"></i>
          </div>
          <a href="{{route('personajes.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Especies</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-dna"></i>
          </div>
          <a href="{{route('especies.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Asentamientos</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-landmark"></i>
          </div>
          <a href="{{route('asentamientos.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Lugares</h3>
          </div>
          <div class="icon">
            <i class="fas fa-tree"></i>
          </div>
          <a href="{{route('lugares.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Conflictos</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <a href="{{route('conflictos.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Crónicas</h3>
          </div>
          <div class="icon">
            <i class="fas fa-book-open"></i>
          </div>
          <a href="#" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Cronologías</h3>
          </div>
          <div class="icon">
            <i class="fas fa-columns"></i>
          </div>
          <a href="{{route('timelines.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Apuntes</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-pencil"></i>
          </div>
          <a href="{{route('articulos')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Enlaces</h3>
          </div>
          <div class="icon">
            <i class="fas fa-file"></i>
          </div>
          <a href="{{route('enlaces.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <!-- small box -->
        <div class="small-box bg-dark">
          <div class="inner">
            <h3>Nombres</h3>
          </div>
          <div class="icon">
            <i class="fa-solid fa-pencil"></i>
          </div>
          <a href="{{route('nombres.index')}}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <!--<img src="/Aeberion/imagenes/Aeberion.jpeg" class="img-fluid" alt="Aeberion.jpeg">-->
  </div>
</section>
@endsection