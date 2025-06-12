<div class="card card-primary shadow-lg">
    <div class="card-header">
      <h3 class="card-title">Buat Outlet Baru</h3>

      <div class="card-tools">
        <button type="button" class="close" data-target="#showcreatemodal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
    </div>
            <form action="{{ route('instances.store') }}" method="POST" id="create-tenant-form">
                @csrf
                <div class="card-body">
                    <div id="modal-errors" class="alert alert-danger" style="display: none;"></div>
                    <div class="form-group">
                        <label for="modal-tenant-name">Nama Outlet</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="modal-tenant-name" name="name" placeholder="Nama outlet" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Masukkan nama outlet baru yang ingin anda buat.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <span id="modal-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Buat Outlet
                    </button>
                    <a href="{{ route('instances.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
    <!-- /.card-body -->
  </div>
