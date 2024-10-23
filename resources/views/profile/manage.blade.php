<form action="{{ url('/profile/update') }}" method="POST" id="form-manage-profile">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content rounded shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Kelola Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username" class="font-weight-bold">Username</label>
                    <input type="text" name="username" id="username" value="{{ auth()->user()->username }}" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="nama" class="font-weight-bold">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ auth()->user()->nama }}" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="password" class="font-weight-bold">Password <small>(Kosongkan jika tidak ingin mengganti)</small></label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-manage-profile").validate({
        rules: {
            username: { required: true },
            nama: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#profile-modal').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        location.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>

@push('css')
<style>
    .modal-content {
        border-radius: 10px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
    }
    .modal-footer {
        border-top: 0;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-shadow: none;
        transition: box-shadow 0.3s;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
    }
</style>
@endpush
