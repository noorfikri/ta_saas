@extends('layouts.adminlte3')

@section('title', 'Daftar Outlet')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Outlet</h3>
        <div class="card-tools">
            <a href="{{ route('instances.create') }}" class="btn btn-primary rounded-pill" data-target="#showcreatemodal" data-toggle='modal' onclick="showCreate()">
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
        <div id="alert-container" class="mb-3"></div>

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
function showCreate(){
    $.ajax({
        type:'POST',
        url:'{{route("instances.showCreate")}}',
        data:{'_token':'<?php echo csrf_token() ?>',
        },
        success: function(data){
            $('#createmodal').html(data.msg);
            // Attach submit handler to the create form when it's loaded
            $('#create-tenant-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = form.find('#modal-spinner');

                // Show spinner and disable submit button
                spinner.show();
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Hide modal
                        $('#showcreatemodal').modal('hide');

                        // Show success message
                        if (response.message) {
                            showAlert(response.message, 'success');
                        } else {
                            showAlert('Outlet baru sedang dibuat.', 'success');
                        }

                        // Refresh the instances table
                        pollStatus();
                        startPollingIfNeeded();
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorHtml = '<ul class="mb-0">';
                            $.each(errors, function(key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                            });
                            errorHtml += '</ul>';
                            $('#modal-errors').html(errorHtml).show();
                        } else {
                            const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat membuat outlet.';
                            $('#modal-errors').html(errorMsg).show();
                        }
                    },
                    complete: function() {
                        // Hide spinner and enable submit button
                        spinner.hide();
                        submitBtn.prop('disabled', false);
                    }
                });
            });
        }
    });
}

$(document).ready(function() {
    let pollingInterval = null;
    let isPolling = false;

    // Initial load
    pollStatus();

    $('#instances-table-body').on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if (!confirm('Sistem akan dihapus beseta data data didalamnya, anda yakin anda mau menghapus sistem ini?')) return;

        const form = $(this);
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
                // Refresh status immediately after delete
                pollStatus();
                // Restart polling if needed
                startPollingIfNeeded();
            },
            error: (xhr) => {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Tidak dapat melakukan proses penghapusan.';
                showAlert(errorMsg, 'danger');
            }
        });
    });

    function startPollingIfNeeded() {
        // Clear any existing interval
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }

        // Start polling every 15 seconds
        pollingInterval = setInterval(pollStatus, 15000);
    }

    function stopPollingIfNeeded() {
        // Stop polling if no instances are in progress
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
            console.log("Seluruh sistem telah terupdate, memberhentikan pemberbaharuan.");
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
                statusBadge = `<span class="badge badge-secondary instance-status" data-status="deleting">Sedang menghapus...</span>`;
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
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" ${!isActionable ? 'disabled' : ''}>
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        `;
    }

    function updateTable(instances) {
        const tbody = $('#instances-table-body');

        if (instances.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center">Anda belum membuat outlet apapun.</td></tr>');
            stopPollingIfNeeded();
            return;
        }

        // Update or create rows efficiently
        instances.forEach(instance => {
            const rowId = `#instance-row-${instance.id}`;
            const rowHtml = renderRow(instance);

            if ($(rowId).length > 0) {
                // Update existing row
                $(rowId).replaceWith(rowHtml);
            } else {
                // Add new row
                tbody.append(rowHtml);
            }
        });

        // Remove rows for instances that no longer exist
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

        // Check if any instances are in progress
        const inProgress = instances.some(i => ['creating', 'pending', 'deleting'].includes(i.status));

        if (inProgress) {
            console.log("Perubahan sistem terdeteksi, memastikan pemberbaharuan berjalan.");
            startPollingIfNeeded();
        } else {
            stopPollingIfNeeded();
        }
    }

    function pollStatus() {
        // Prevent multiple simultaneous requests
        if (isPolling) {
            console.log("Polling sedang berjalan, melewatkan permintaan baru.");
            return;
        }

        isPolling = true;
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
                isPolling = false;
            });
    }
});
</script>
@endsection
