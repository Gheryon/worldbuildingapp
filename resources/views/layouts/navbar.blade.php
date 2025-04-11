@section('navbar')
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    @yield('navbar-buttons')  
  </ul>
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    @yield('navbar-search')    
  </ul>
</nav>
<!-- /.navbar -->
@endsection