<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta_description', 'Worldbuilding App - Herramienta para crear y gestionar mundos ficticios')">
  <link rel="canonical" href="@yield('canonical_url', url()->current())">
  <meta name="robots" content="@yield('robots_meta', 'index, follow')">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: blob: https:; connect-src 'self';">
  @php
    $ldData = [
      '@context' => 'https://schema.org',
      '@type' => 'WebApplication',
      'name' => config('app.name', 'Worldbuilding App'),
      'url' => config('app.url'),
      'description' => trim($__env->yieldContent('meta_description', 'Worldbuilding App - Herramienta para crear y gestionar mundos ficticios')),
      'applicationCategory' => 'ProductivityApplication',
      'operatingSystem' => 'WEB',
      'publisher' => [
        '@type' => 'Organization',
        'name' => config('app.name', 'Worldbuilding App'),
      ],
    ];
  @endphp
  <script type="application/ld+json">
{!! json_encode($ldData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
  </script>
  @yield('title')

  <!-- Google Font: Source Sans Pro -->
  <!--<link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap" integrity="sha384-LwTn3VUjhfCQ7fNTgs5njQpcKigDry8Anvg+3XojQS1MpAX9Yw9gtyMF5SGibvN2" crossorigin="anonymous">-->

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Metamorphous&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link rel="preload" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}"></noscript>
  <!-- Select2 -->
  <link rel="preload" href="{{asset('dist/css/select2.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('dist/css/select2.min.css')}}"></noscript>
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" as="style" integrity="sha384-dZ/kdA+7jiSms5Z+NkfRPANv1sHvWqS7e51A6ywK9wsJztsDG2q4gA4Ltui+/1yW" crossorigin="anonymous" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" integrity="sha384-dZ/kdA+7jiSms5Z+NkfRPANv1sHvWqS7e51A6ywK9wsJztsDG2q4gA4Ltui+/1yW" crossorigin="anonymous"></noscript>
  <!-- Theme style -->
  <link rel="preload" href="{{asset('dist/css/adminlte.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}"></noscript>
  <!-- Summernote -->
  <link rel="preload" href="{{asset('dist/css/summernote-bs4.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('dist/css/summernote-bs4.min.css')}}"></noscript>
  <!-- Toastr -->
  <link rel="preload" href="{{asset('dist/css/toastr.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('dist/css/toastr.min.css')}}"></noscript>

  <link rel="preload" href="{{asset('dist/css/styles.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{asset('dist/css/styles.css')}}"></noscript>

  @yield('specific-cases')
</head>

<body class="hold-transition sidebar-mini layout-fixed" id="_body_">
  <a href="#main-content" class="skip-link sr-only">Saltar al contenido principal</a>
  <div class="wrapper">
    <!-- Navbar -->
    @include('layouts.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.menu')

    <main id="main-content" class="content">
      {{-- Cada vista debe proveer su propio <h1> principal --}}
      @yield('main-content')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <div class="container-fluid p-5 overflow-auto">
          @yield('content')
        </div>
      </div>
      <!-- /.content-wrapper -->
    </main>

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
    document.addEventListener('DOMContentLoaded', function() {
      const sessionData = {
        success: @json(session('success')),
        error: @json(session('error')),
        info: @json(session('info')),
        warning: @json(session('warning'))
      };
      showNotifications(sessionData);
    });
  </script>
  @yield('specific-scripts')

</body>

</html>