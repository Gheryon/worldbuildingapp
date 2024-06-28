@section('menu')

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  <li class="nav-item">
    <a href="{{route('organizaciones.index')}}" class="nav-link">
      <i class="nav-icon fa-solid fa-building-columns"></i>
      <p>Instituciones</p>
    </a>
  </li>
  <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon fa-solid fa-place-of-worship"></i>
      <p>Religiones<i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="createReligion.php" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>Nueva religión</p>
        </a>
      </li>
    </ul>
  </li>
  <li class="nav-item">
    <a href="{{url('/personajes/index')}}" class="nav-link">
      <i class="nav-icon fa-solid fa-people-group"></i>
      <p>Personajes</p>
    </a>
  </li>
  <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon fa-solid fa-dna"></i>
      <p>Especies</p>
    </a>
  </li>
  <li class="nav-item">
    <a href="asentamientos.php" class="nav-link">
      <i class="nav-icon fa-solid fa-landmark"></i>
      <p>
        Asentamientos
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="lugares.php" class="nav-link">
      <i class="nav-icon fas fa-tree"></i>
      <p>
        Lugares
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="conflictos.php" class="nav-link">
      <i class="nav-icon fa-solid fa-shield-halved"></i>
      <p>
        Guerras y batallas
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
  <li class="nav-header">OTROS</li>
  <li class="nav-item">
    <a href="{{url('/articulos/index')}}" class="nav-link">
      <i class="nav-icon fa-solid fa-book-open"></i>
      <p>
        Apuntes y relatos
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="notasNombres.php" class="nav-link">
      <i class="nav-icon fas fa-pencil-alt"></i>
      <p>
        Lista de nombres
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="enlaces.php" class="nav-link">
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
@endsection