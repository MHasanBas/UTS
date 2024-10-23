<form action="{{ url('/profile/update-photo') }}" method="POST" enctype="multipart/form-data" id="form-change-photo">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content rounded shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_pfp" class="font-weight-bold">Pilih Foto Profil</label>
                    <div class="custom-file">
                        <input type="file" name="file_pfp" id="file_pfp" class="custom-file-input" required>
                        <label class="custom-file-label" for="file_pfp">Pilih file</label>
                    </div>
                    <small id="error-file_pfp" class="error-text form-text text-danger"></small>
                    <small class="form-text text-muted mt-1">Format yang didukung: JPG, JPEG, PNG (max 2MB)</small>
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
    // File input label update
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    $("#form-change-photo").validate({
        rules: { file_pfp: { required: true, extension: "jpg|jpeg|png" } },
        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#profile-modal').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        $('#profile-pic').attr('src', response.newProfilePicturePath);
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
    .custom-file-input:focus ~ .custom-file-label {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
    }
</style>
@endpush
