<div class="card card-outline card-primary shadow-lg p-0">
    <div class="card-header d-flex justify-content-between align-items-center my-0 py-0 border-0">
        <div class="bg-primary py-2 px-3 my-0 rounded-bottom rounded-3">
            <h3 class="card-title"> <i class="fa-solid fa-square-plus"></i> Buat Sistem Toko Baru</h3>
        </div>
    </div>
    <form action="{{ route('instances.store') }}" method="POST" id="create-tenant-form">
        @csrf
        <div class="card-body">
            <div id="modal-errors" class="alert alert-danger" style="display: none;"></div>
            <div class="form-group">
                <label for="modal-tenant-name">Nama Toko</label>
                <small class="form-text text-muted">Masukkan nama toko yang ingin anda masukkan.</small>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="modal-tenant-name" name="name" placeholder="Nama Toko" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="modal-admin-email">Email Akun Admin</label>
                <small class="form-text text-muted">Masukkan Email Akun Admin Yang Anda Ingin Gunakan <strong>SIMPAN EMAIL INI KARENA AKAN DIGUNAKAN UNTUK MASUK KE SISTEM</strong></small>
                <input type="email" class="form-control" id="modal-admin-email" name="admin_email" placeholder="Email Admin" required>
            </div>
            <div class="form-group">
                <label for="modal-admin-password">Password Akun Admin</label>
                <small class="form-text text-muted">Masukkan Password Akun Admin Yang Anda Ingin Gunakan <strong>SIMPAN PASSWORD INI KARENA AKAN DIGUNAKAN UNTUK MASUK KE SISTEM</strong></small>
                <input type="password" class="form-control" id="modal-admin-password" name="admin_password" placeholder="Password Admin" required>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-12">
                <a href="{{ route('instances.index') }}" class="btn btn-outline-danger rounded-pill"> <i class="fa-solid fa-xmark"></i> Batal</a>
                <button type="submit" class="btn btn-success float-right rounded-pill">
                    <i class="fa-solid fa-floppy-disk"></i> Buat Sistem Toko Baru
                </button>
            </div>
        </div>
    </form>
</div>
