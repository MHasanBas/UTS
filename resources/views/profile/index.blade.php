@extends('layouts.template')
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{ asset('images/pfp/' . auth()->user()->avatar) }}" 
                class="rounded-circle shadow-lg mb-3" 
                alt="Profile Picture" 
                style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #f1f1f1;"
                id="profile-pic">
        </div>
        <div class="col-md-9">
            <form class="p-4 bg-white shadow-sm rounded">
                <div class="form-group row">
                    <label for="username" class="col-sm-3 col-form-label text-muted">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" value="{{ auth()->user()->username }}" readonly>
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label for="nama" class="col-sm-3 col-form-label text-muted">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" value="{{ auth()->user()->nama }}" readonly>
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label for="password" class="col-sm-3 col-form-label text-muted">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" value="********" readonly>
                    </div>
                </div>
            </form>
            <button class="btn btn-gradient-primary mt-4 px-4 py-2 rounded-pill shadow-sm" onclick="changeProfilePic()">
                Ubah Foto Profil
            </button>
            <button class="btn btn-gradient-secondary mt-4 px-4 py-2 rounded-pill shadow-sm" onclick="manageProfile()">
                Kelola Profil
            </button>
        </div>
    </div>
</div>

<div id="profile-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
</div>
@endsection

@push('js')
<script>
    function changeProfilePic() {
        $('#profile-modal').load('{{ url("/profile/change-photo") }}', function() {
            $('#profile-modal').modal('show');
        });
    }
    function manageProfile() {
        $('#profile-modal').load('{{ url("/profile/manage") }}', function() {
            $('#profile-modal').modal('show');
        });
    }
</script>
@endpush

@push('css')
<style>
    .btn-gradient-primary {
        background: linear-gradient(to right, #007bff, #0056b3);
        color: white;
    }
    .btn-gradient-secondary {
        background: linear-gradient(to right, #6c757d, #5a6268);
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
    .shadow-sm {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .rounded-pill {
        border-radius: 50px;
    }
</style>
@endpush
