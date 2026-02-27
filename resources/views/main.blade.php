@extends('layouts.index')

@section('title')
<title id="title">Worldbuilding app</title>
@endsection

@section('navbar-buttons')
@endsection

@section('content')
<div class="content-header bg-dark mb-4 shadow-sm" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/Aeberion/imagenes/Aeberion.jpeg'); background-size: cover; background-position: center; border-radius: 0 0 15px 15px; min-height: 90px; display: flex; align-items: center;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 offset-sm-2 text-center">
        <h1 class="m-0 text-white font-weight-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); letter-spacing: 2px;">
            Worldbuilding App
        </h1>
        <p class="text-light mt-2 d-none d-md-block" style="opacity: 0.9;">
            Gestiona la mitología, geografía y crónicas de un universo.
        </p>
      </div>
    </div>
  </div>
</div>

<section class="content">
    <div class="container-fluid">
      
        {{-- Sección: El Mundo --}}
        <h5 class="mb-3 mt-4 text-muted"><i class="fas fa-globe-americas mr-2"></i>Geografía y Vida</h5>
        <div class="row">
          <x-dashboard-card route="lugares.index" title="Lugares" icon="fa-mountain-sun" bg="bg-dark" />
          <x-dashboard-card route="asentamientos.index" title="Asentamientos" icon="fa-house" bg="bg-dark" />
          <x-dashboard-card route="especies.index" title="Especies" icon="fa-dna" bg="bg-dark" />
          <x-dashboard-card route="construcciones.index" title="Construcciones" icon="fa-building" bg="bg-dark" />
        </div>

        {{-- Sección: Historia y Cultura --}}
        <h5 class="mb-3 mt-4 text-muted"><i class="fas fa-scroll mr-2"></i>Historia y Sociedad</h5>
        <div class="row">
          <x-dashboard-card route="personajes.index" title="Personajes" icon="fa-people-group" bg="bg-purple" />
          <x-dashboard-card route="organizaciones.index" title="Instituciones" icon="fa-building-columns" bg="bg-purple" />
          <x-dashboard-card route="religiones.index" title="Religiones" icon="fa-place-of-worship" bg="bg-purple" />
          <x-dashboard-card route="conflictos.index" title="Conflictos" icon="fa-shield-halved" bg="bg-purple" />
          <x-dashboard-card route="timelines.index" title="Cronologías" icon="fas fa-columns" bg="bg-purple" />
        </div>

        {{-- Sección: Herramientas --}}
        <h5 class="mb-3 mt-4 text-muted"><i class="fas fa-toolbox mr-2"></i>Herramientas de Escritura</h5>
        <div class="row">
          <x-dashboard-card route="articulos.index" title="Apuntes" icon="fa-pencil" bg="bg-olive" />
          <x-dashboard-card route="nombres.index" title="Nombres" icon="fa-signature" bg="bg-olive" />
          <x-dashboard-card route="enlaces.index" title="Enlaces" icon="fas fa-file" bg="bg-olive" />
          <x-dashboard-card route="galeria.index" title="Galería" icon="fa-image" bg="bg-olive" />
        </div>

    </div>
</section>

<style>
    /* Efecto moderno de elevación al pasar el ratón */
    .small-box {
        transition: transform .2s, box-shadow .2s;
        border-radius: 12px; /* Esquinas más suaves */
        overflow: hidden;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
</style>
@endsection