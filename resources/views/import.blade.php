@extends('layout')

@section('main')
    <h2 style="text-align: center;margin-top:50px"><i class="fa-solid fa-file-import"></i> &nbsp; Import</h2>
    <br>
    <br>

    <div class="row" style="padding: 10px">
        <a type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="btn btn-dark btn-icon-split"
            style="width: 12rem; margin:auto">
            <span class="icon text-white-50">
                <i class="fa-solid fa-user-plus" style="color: white"></i>
            </span>
            <span class="text" style="width: 200px"> &nbsp; Create User</span>
        </a>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#importlink').css('color', 'white');
        });
    </script>
@endsection
