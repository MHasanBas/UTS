<form action="{{ url('/profile_ajax') }}" method="POST" id="form-profile" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_pfp">Select File</label>
                    <input type="file" name="file_pfp" id="file_pfp" class="form-control" required>
                    <small id="error-file_pfp" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-profile").validate({
            rules: {
                file_pfp: { 
                    required: true,
                    extension: "jpg|jpeg|png" 
                }
            },
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
                            $('#myModal').modal('hide'); 
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('.nav-item.dropdown img').attr('src', response.newProfilePicturePath);
                        } else { 
                            $('.error-text').text(''); 
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]); 
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Failed to upload the profile picture. Please try again.'
                        });
                    }
                });
                return false; 
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>