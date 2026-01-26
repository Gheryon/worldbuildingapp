<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('title')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- Summernote -->
  <link rel="stylesheet" href="{{asset('dist/css/summernote-bs4.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('dist/css/toastr.min.css')}}">

  <link rel="stylesheet" href="{{asset('dist/css/styles.css')}}">
</head>

<body class="hold-transition sidebar-mini layout-fixed" id="_body_">
  <div class="wrapper">
    <!-- Navbar -->
    @include('layouts.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.menu')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="container-fluid p-5 overflow-auto">
        @yield('content')
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- Default to the left -->
      <strong>Copyright &copy; 2024-<?php echo date("Y") ?>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
  <!-- Summernote -->
  <script src="{{asset('dist/js/summernote-bs4.min.js')}}"></script>
  <!-- toastr -->
  <script src="{{asset('dist/js/toastr.min.js')}}"></script>
  <!-- select2 -->
  <script src="{{asset('dist/js/select2.min.js')}}"></script>

  <script src="{{asset('dist/js/notifications.js')}}"></script>
  <script>
    // Pasamos las variables de sesión de PHP a un objeto JS
    const sessionData = {
      success: "{{ session('message') }}",
      error: "{{ session('error') }}",
      info: "{{ session('info') }}",
      warning: "{{ session('warning') }}"
    };

    // Ejecutamos la función del archivo externo
    showNotifications(sessionData);
  </script>
  @yield('specific-scripts')

</body>

</html>