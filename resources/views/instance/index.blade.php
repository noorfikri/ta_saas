@extends('layouts.adminlte3')

@section('title', 'Daftar Outlet')

@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Daftar Sistem Informasi Toko</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Daftar Sistem Informasi Toko</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="alert alert-primary">
        <h5 class="color"><strong><i class="fa-solid fa-circle-info"></i> Bantuan</strong></h5>
        Halaman ini adalah halaman <strong>Daftar Sistem Informasi Toko</strong>. Anda dapat membuat <strong>Sistem Informasi Toko</strong> baru sesuai dengan toko atau bisnis yang anda miliki.<br>
        <br> <strong>Cara penggunaan :</strong>
        <ul>
            <li>
                <p class="my-0 py-0">
                    <i class="fas fa-plus-circle"></i> <strong>Buat Sistem Toko Baru</strong> : Untuk membuat <strong>Sistem Informasi Toko Baru</strong>. Sistem memerlukan kurang lebih <strong>10 - 30 Menit</strong> agar sistem dapat digunakan dengan baik. Pastikan anda menyimpan <strong>Email dan Password Admin</strong> dikarenakan akun tersebut digunakan untuk masuk kedalam sistem.
                </p>
            </li>
            <li>
                <p class="my-0 py-0">
                    <i class="fas fa-trash"></i> <strong> Hapus Outlet </strong>: Untuk melakukan <strong>Penghapusan Sistem Informasi Toko</strong> yang telah terbentuk. <strong>Seluruh Data dan Informasi Yang Tersimpan Didalam Sistem Akan Dihapus.</strong>
                </p>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <a class="btn btn-primary rounded-pill" href="{{ route('instances.create') }}" data-target="#showcreatemodal" data-toggle='modal' onclick="showCreate()">
                                <i class="fas fa-plus-circle"></i> Buat Sistem Toko Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success"  id="success-alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div id="alert-container" class="mb-3"></div>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Toko</th>
                                    <th>Status</th>
                                    <th style="width: 40%;">Pesan</th>
                                    <th>Link URL Aplikasi Web</th>
                                    <th>Tanggal Pembuatan</th>
                                    <th>Opsi</th>
                                    <th>
                                        <div class="modal fade" id="showcreatemodal" tabindex="-1" role="basic" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content" id="createmodal">
                                                    <img src="{{ asset('assets/img/ajax-modal-loading.gif')}}" alt="" class="loading">
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="instances-table-body">
                                @if ($instances->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Anda belum membuat Sistem Informasi apapun.</td>
                                </tr>
                                @else
                                @foreach ($instances as $instance)
                                    <tr id="instance-row-{{ $instance->id }}">
                                        <td>{{ $instance->name }}</td>
                                        <td>
                                            @switch($instance->status)
                                                @case('active')
                                                    <span class="badge badge-success instance-status" data-status="active">Aktif</span>
                                                    @break
                                                @case('creating')
                                                @case('pending')
                                                    <span class="badge badge-warning instance-status" data-status="creating">Sedang membuat...</span>
                                                    @break
                                                @case('deleting')
                                                    <span class="badge badge-secondary instance-status" data-status="deleting">Sedang menghapus...</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-danger instance-status" data-status="failed">Gagal</span>
                                            @endswitch
                                        </td>
                                        <td><div style="max-width: 300px; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word;"><small>{{ $instance->message ?: 'Tidak ada pesan' }}</small></div></td>
                                        <td>
                                            @if($instance->app_url)
                                                <a href="http://{{ $instance->app_url }}" target="_blank">http://{{ $instance->app_url }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $instance->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        <td>
                                            @php $isActionable = in_array($instance->status, ['active', 'failed', 'delete_failed']); @endphp
                                            <a class="btn btn-danger btn-sm rounded-pill"
                                                data-target="#deleteInstanceModal{{ $instance->id }}" data-toggle='modal' {{ !$isActionable ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                        <td>
                                            <div class="modal fade" id="deleteInstanceModal{{ $instance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="card modal-body card-outline card-danger shadow-lg p-0">
                                                            <form class="delete-instance-form" method="POST" action="{{ route('instances.destroy', $instance->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-header d-flex justify-content-between my-0 py-0 border-0">
                                                                    <div class="bg-danger py-2 px-3 my-0 rounded-bottom rounded-3">
                                                                        <h4 class="modal-title" id="deleteModalLabel"> <i class="fa-solid fa-trash"></i> Hapus Outlet</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Apakah Anda yakin ingin menghapus outlet "{{ $instance->name }}"?</p>
                                                                    <small>Sistem akan dihapus beserta data data didalamnya.</small>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-outline-dark rounded-pill" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Batal</button>
                                                                    <button type="submit" class="btn btn-danger rounded-pill"><i class="fas fa-trash"></i> Hapus Outlet</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script>
const csrfToken = '{{ csrf_token() }}';

function showCreate(){
    $.ajax({
        type:'POST',
        url:'{{route("instances.showCreate")}}',
        data:{'_token':'<?php echo csrf_token() ?>',
        },
        success: function(data){
            $('#createmodal').html(data.msg);
        }
    });
}

$(document).ready(function() {
    let timerInterval = null;
    let isTimerRunning = false;

    timerStatus();

    $('#showcreatemodal').on('submit', '#create-tenant-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');

        submitBtn.attr('disabled', 'disabled');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#showcreatemodal').modal('hide');

                showAlert(response.message, 'success');

                timerStatus(true);
                startTimer();
            },
            error: function(responseError) {
                if (responseError.status === 422) {
                    const errors = responseError.responseJSON.errors;
                    let errorHtml = '<ul class="mb-0">';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';
                    $('#modal-errors').html(errorHtml).show();
                } else {
                    let errorMsg;
                    if (responseError.responseJSON && responseError.responseJSON.message) {
                        errorMsg = responseError.responseJSON.message;
                    } else {
                        errorMsg = 'Terjadi kesalahan yang tidak diketahui saat membuat Sistem.';
                    }
                    $('#modal-errors').html(errorMsg).show();
                }
            },
            complete: function() {
                submitBtn.removeAttr('disabled');

                timerStatus();
            }
        });
    });

    $(document).on('submit', '.delete-instance-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const modal = form.closest('.modal');
        modal.modal('hide');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: (response) => {
                if (response.message) {
                    showAlert(response.message, 'info');
                } else {
                    showAlert('Penghapusan sistem telah dimulai.', 'info');
                }

                timerStatus();

                startTimer();
            },
            error: (responseError) => {
                    let errorMsg;
                    if (responseError.responseJSON && responseError.responseJSON.message) {
                        errorMsg = responseError.responseJSON.message;
                    } else {
                        errorMsg = 'Tidak dapat melakukan proses penghapusan.';
                    }
                showAlert(errorMsg, 'danger');
            }
        });
    });

    function startTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        timerInterval = setInterval(timerStatus, 15000);
    }

    function stopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            console.log("Seluruh sistem telah terupdate, memberhentikan pembaruan.");
        }
    }

    function showAlert(message, type = 'success') {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                              ${message}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           </div>`;
        $('#alert-container').html(alertHtml).find('.alert').delay(5000).fadeOut(300, function() { $(this).remove(); });
    }

    function renderRow(instance) {
        const isActionable = ['active', 'failed', 'delete_failed'].includes(instance.status);
        let statusBadge = '';

        switch (instance.status) {
            case 'active':
                statusBadge = `<span class="badge badge-success instance-status" data-status="active">Aktif</span>`;
                break;
            case 'creating':
            case 'pending':
                statusBadge = `<span class="badge badge-warning instance-status" data-status="creating">Sedang membuat...</span>`;
                break;
            case 'deleting':
                statusBadge = `<span class="badge badge-danger instance-status" data-status="deleting">Sedang menghapus...</span>`;
                break;
            default:
                statusBadge = `<span class="badge badge-danger instance-status" data-status="failed">Gagal</span>`;
        }

        const messageHtml = `<div style="max-width: 300px; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word;"><small>${instance.message || 'Tidak ada pesan'}</small></div>`;
        const url = instance.app_url ? `<a href="http://${instance.app_url}" target="_blank">http://${instance.app_url}</a>` : 'N/A';
        const createdAt = new Date(instance.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
        const deleteUrl = "{{ route('instances.destroy', ':id') }}".replace(':id', instance.id);
        const disabledAttr = !isActionable ? 'disabled' : '';

        return `
            <tr id="instance-row-${instance.id}">
                <td>${instance.name}</td>
                <td>${statusBadge}</td>
                <td>${messageHtml}</td>
                <td>${url}</td>
                <td>${createdAt}</td>
                <td>
                    <a class="btn btn-danger btn-sm rounded-pill"
                            data-target="#deleteInstanceModal${instance.id}"
                            data-toggle="modal"
                            ${disabledAttr}>
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
                <td>
                    <div class="modal fade" id="deleteInstanceModal${instance.id}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card modal-body card-outline card-danger shadow-lg p-0">
                                    <form class="delete-instance-form" method="POST" action="${deleteUrl}">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <div class="modal-header d-flex justify-content-between my-0 py-0 border-0">
                                            <div class="bg-danger py-2 px-3 my-0 rounded-bottom rounded-3">
                                                <h4 class="modal-title"> <i class="fa-solid fa-trash"></i> Hapus Outlet</h4>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus outlet "${instance.name}"?</p>
                                            <small>Sistem akan dihapus beserta data data didalamnya.</small>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-outline-dark rounded-pill" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill"><i class="fas fa-trash"></i> Hapus Outlet</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }

    function updateTable(instances) {
        const tbody = $('#instances-table-body');

        if (instances.length === 0) {
            tbody.html('<tr><td colspan="7" class="text-center">Anda belum membuat Sistem Informasi apapun.</td></tr>');
            stopTimer();
            return;
        }

        const tdata = tbody.find('td[colspan="7"]');
        if (tdata.length) {
            tdata.parent().remove();
        }

        instances.forEach(instance => {
            const rowId = `#instance-row-${instance.id}`;
            const rowHtml = renderRow(instance);

            if ($(rowId).length > 0) {
                $(rowId).replaceWith(rowHtml);
            } else {
                tbody.append(rowHtml);
            }
        });

        const instanceIds = instances.map(i => i.id);
        tbody.find('tr').each(function() {
            const rowId = $(this).attr('id');
            if (rowId) {
                const id = parseInt(rowId.replace('instance-row-', ''));
                if (!instanceIds.includes(id)) {
                    $(this).remove();
                }
            }
        });

        const inProgress = instances.some(i => ['creating', 'pending', 'deleting'].includes(i.status));

        if (inProgress) {
            console.log("Perubahan sistem terdeteksi, menjalankan timer.");
            startTimer();
        } else {
            stopTimer();
        }
    }

    function timerStatus(force = false) {
        if (isTimerRunning && !force) {
            console.log("timer sedang berjalan, melewati permintaan baru.");
            return;
        }

        isTimerRunning = true;
        console.log("Mengambil perubahan sistem untuk tabel");

        $.get("{{ route('instances.status') }}")
            .done(data => {
                console.log('Berhasil mengambil informasi sistem, mengupdate tabel.');
                updateTable(data.instances);
            })
            .fail(err => {
                console.error("Gagal mengambil informasi sistem:", err);
            })
            .always(() => {
                isTimerRunning = false;
            });
    }
});
</script>
@endsection
