@section('menu')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{url('/')}}" class="brand-link">
    <h2 class="brand-text text-center font-weight-light">Inicio</h2>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2 mb-5">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{route('organizaciones.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-building-columns"></i>
            <p>Instituciones</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('personajes.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-people-group"></i>
            <p>Personajes</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('religiones.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-place-of-worship"></i>
            <p>Religiones</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('especies.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-dna"></i>
            <p>Especies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('asentamientos.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-house"></i>
            <p>
              Asentamientos
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('lugares.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-mountain-sun"></i>
            <p>
              Lugares
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('construcciones.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-building"></i>
            <p>
              Construcciones
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('conflictos.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-shield-halved"></i>
            <p>
              Conflictos y batallas
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('timelines.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Cronologías
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('relatos')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-book-open"></i>
            <p>
              Relatos
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('galeria.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-image"></i>
            <p>
              Galería de imágenes
            </p>
          </a>
        </li>
        <li class="nav-header">OTROS</li>
        <li class="nav-item">
          <a href="{{route('articulos')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-book-open"></i>
            <p>
              Apuntes
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('nombres.index')}}" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>
              Lista de nombres
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('enlaces.index')}}" class="nav-link">
            <i class="nav-icon fas fa-file"></i>
            <p>Enlaces</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('config.index')}}" class="nav-link">
            <i class="nav-icon fa-solid fa-gear"></i>
            <p>Opciones</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

@endsection