@extends('layouts.adminlte3')

@section('content')
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Profil</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Profil Pengguna</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="alert alert-primary">
        <h5 class="color"><strong><i class="fa-solid fa-circle-info"></i> Bantuan</strong></h5>
        Halaman ini adalah halaman <strong>Profil Akun</strong>. Anda dapat mengubah informasi akun anda pada halaman ini.<br>
        <br> <strong>Cara penggunaan :</strong>
        <ul>
            <li>
                <p class="my-0 py-0">
                    <i class="fa-solid fa-pen-to-square"></i> <strong>Ubah Informasi Pengguna</strong> : Untuk mengubah informasi akun anda seperti nama, email, dan password akun. Simpan perubahan akun dengan menekan tombol <i class="fa-solid fa-floppy-disk"></i> <strong>Simpan Perubahan Akun</strong>
                </p>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">

              <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

              <p class="text-muted text-center">{{ Auth::user()->email }}</p>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-outline card-primary p-0">
            <div class="card-header d-flex justify-content-between my-0 py-0 border-0">
              <div class="bg-primary py-2 px-3 my-0 rounded-bottom rounded-3">
                <h3 class="card-title"><i class="fa-solid fa-pen-to-square"></i> Ubah Informasi Pengguna</h3>
              </div>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane active" id="settings">
                <form class="form-horizontal" method="POST" action="{{ route('profile.update', Auth::user()->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="inputName" placeholder="Nama" value="{{ old('name', Auth::user()->name) }}">
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="inputEmail" placeholder="Email" value="{{ old('email', Auth::user()->email) }}">
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputCurrentPassword" class="col-sm-2 col-form-label">Kata Sandi Saat Ini</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="inputCurrentPassword" placeholder="Kata Sandi Saat Ini">
                            @error('current_password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputNewPassword" class="col-sm-2 col-form-label">Kata Sandi Baru</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="inputNewPassword" placeholder="Kata Sandi Baru">
                            @error('new_password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputNewPasswordConfirmation" class="col-sm-2 col-form-label">Konfirmasi Kata Sandi Baru</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="new_password_confirmation" id="inputNewPasswordConfirmation" placeholder="Konfirmasi Kata Sandi Baru">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success rounded-pill"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Akun</button>
                        </div>
                    </div>
                </form>
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  @endsection

