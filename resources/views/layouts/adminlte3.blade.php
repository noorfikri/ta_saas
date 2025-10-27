<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Informasi Manajemen Toko </title>

    <!-- Favicon -->
   <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <script src="https://kit.fontawesome.com/a85611667c.js" crossorigin="anonymous"></script>
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">

  <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script type="text/javascript" src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE -->
    <script src="{{asset('assets/js/adminlte.js')}}"></script>

@yield('javascript')

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin')}}" class="nav-link">Beranda</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-light">

            <p>
              {{ Auth::user()->name }}
              <small>Terdaftar Sejak {{ Auth::user()->created_at->format('M. Y') }}</small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{ route('profile') }}" class="btn btn-default btn-outline-dark rounded-pill"> <i class="fa-solid fa-user"></i> Profil</a>
            <a class="btn btn-danger rounded-pill float-right" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-light-indigo">
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link">
      <span class="brand-text font-weight-light">Administrasi Sistem Toko</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="{{ route('profile') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">Manajemen Tokoku</li>
        <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fa-solid fa-boxes-stacked"></i>
          <p>
            Tokoku
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('instances.index') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Daftar Tokoku</p>
            </a>
          </li>
        </ul>
        </li>
        <li class="nav-header">Akun</li>
        <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fa-solid fa-user"></i>
          <p>
            Akun
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('profile') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Akun</p>
            </a>
          </li>
        </ul>
      </li class="bg-danger">
        <li class="nav-header">Keluar</li>
        <li class="nav-item">
        <a class="nav-link bg-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="nav-icon fa-solid fa-sign-out-alt"></i>
          <p>Keluar</p>
        </a>
        </li>
      </ul>
    </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    @yield('content')

  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
</div>
<!-- ./wrapper -->

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{asset('assets/js/demo.js')}}"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('assets/js/pages/dashboard3.js')}}"></script> -->
</body>
</html>
