@extends('layouts.adminlte3')

@section('title', 'Daftar Outlet')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Outlet</h3>
        <div class="card-tools">
            <a href="{{ route('instances.create') }}" class="btn btn-primary" data-target="#showcreatemodal" data-toggle='modal' onclick="showCreate()">
                <i class="fas fa-plus-circle"></i> Buat Outlet Baru
            </a>
        </div>
        <div class="modal fade" id="showcreatemodal" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" id="createmodal">
                        <img src="{{ asset('assets/img/ajax-modal-loading.gif')}}" alt="" class="loading">
                    </div>
                </div>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success"  id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Outlet</th>
                    <th>Status</th>
                    <th style="width: 40%;">Pesan</th>
                    <th>Link URL Aplikasi Web</th>
                    <th>Tanggal Pembuatan</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody id="instances-table-body">

            </tbody>
        </table>
    </div>
</div>
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    let pollingInterval;

    $('#create-instance-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'), method: 'POST', data: form.serialize(),
            beforeSend: () => { $('#modal-submit-button').prop('disabled', true).find('span').show(); $('#modal-errors').hide(); },
            success: (response) => {
                if (response.success) {
                    $('#createInstanceModal').modal('hide');
                    form[0].reset();
                    showAlert('Pembuatan sistem untuk sistem "' + response.instance.name + '" sedang berjalan', 'success');
                    pollStatus(pollingInterval);
                }
            },
            error: (jqXHR) => {
                const errorsContainer = $('#modal-errors');
                if (jqXHR.status === 422) {
                    let errors = Object.values(jqXHR.responseJSON.errors).map(e => `<li>${e[0]}</li>`).join('');
                    errorsContainer.html(`<ul>${errors}</ul>`).show();
                } else {
                    errorsContainer.html('Terjadi sebuah kesalahan saat memproses formulir.').show();
                }
            },
            complete: () => $('#modal-submit-button').prop('disabled', false).find('span').hide()
        });
    });

    $('#instances-table-body').on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if (!confirm('This will permanently delete the instance and all its data. Are you sure?')) return;

        const form = $(this);
        $.ajax({
            url: form.attr('action'), method: 'POST', data: form.serialize(),
            success: (response) => {
                showAlert(response.message, 'info');
                pollStatus(pollingInterval);
            },
            error: () => showAlert('Tidak dapat melakukan proses penghapusan.', 'danger')
        });
    });

    pollStatus(pollingInterval);
});

function showCreate(){
    $.ajax({
        type:'POST',
        url:'{{route("instances.showCreate")}}',
        data:{'_token':'<?php echo csrf_token() ?>',
        },
        success: function(data){
            $('#createmodal').html(data.msg)

            processCreate();
        }
    });
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
                statusBadge = `<span class="badge badge-warning instance-status" data-status="creating"></i> Sedang membuat...</span>`;
                break;
            case 'deleting':
                statusBadge = `<span class="badge badge-secondary instance-status" data-status="deleting"></i> Sedang menghapus...</span>`;
                break;
            default:
                statusBadge = `<span class="badge badge-danger instance-status" data-status="failed">Gagal</span>`;
        }

        const messageHtml = `<div style="max-width: 300px; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word;"><small>${instance.message || 'Tidak ada pesan'}</small></div>`;
        const url = instance.app_url ? `<a href="http://${instance.app_url}" target="_blank">${instance.app_url}</a>` : 'N/A';
        const createdAt = new Date(instance.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
        let deleteUrl = "{{ route('instances.destroy', ':id') }}".replace(':id', instance.id);
        const csrfToken = '{{ csrf_token() }}';

        return `
            <tr id="instance-row-${instance.id}">
                <td>${instance.name}</td>
                <td>${statusBadge}</td>
                <td>${messageHtml}</td>
                <td>${url}</td>
                <td>${createdAt}</td>
                <td>
                    <form action="${deleteUrl}" method="POST" class="delete-form">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm" ${!isActionable ? 'disabled' : ''}>
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        `;
    }

    function updateTable(instances, pollingInterval) {

        const tbody = $('#instances-table-body');
        tbody.empty();

        if (instances.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center">Anda belum membuat outlet apapun.</td></tr>');
        } else {
            instances.forEach(instance => {
                tbody.append(renderRow(instance));
            });
        }

        const inProgress = instances.some(i => ['creating', 'pending', 'deleting'].includes(i.status));

        if (inProgress && !pollingInterval) {
            console.log("Perubahan sistem terdeteksi, mulai pemberbaharuan.");
            pollingInterval = setInterval(pollStatus, 15000);
        } else if (!inProgress && pollingInterval) {
            console.log("Seluruh sistem telah terupdate, memberhentikan pemberbaharuan.");
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function pollStatus(pollingInterval) {
        console.log("Mengambil perubahan sistem untuk tabel");
        $.get("{{ route('instances.status') }}")
            .done(data => {
                console.log('Berhasil mengambil informasi sistem, mengupdate tabel.');
                updateTable(data.instances,pollingInterval);
            })
            .fail(err => {
                console.error("Gagal mengambil informasi sistem:", err);
                if (pollingInterval) clearInterval(pollingInterval);
            });
    }
</script>
@endsection
