@extends('layouts.adminlte3auth')

@section('content')
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="" class="h1"><b>Sistem Informasi</b> Tokoku</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Silahkan Masukkan Informasi Akun Anda Untuk Masuk</p>

      <form action="{{route('login')}}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="input-group mb-3">
          <input id="password" type="password" class="form-control" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="row">
          <div class="col-6">
            <p>Tidak Memiliki Akun? <a href="register" class="text-center">Buat Akun Baru</a></p>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-primary rounded-pill btn-block">Masuk  <i class="fa-solid fa-arrow-right-to-bracket"></i></button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
@endsection
