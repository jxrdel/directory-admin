@extends('layout')

@section('styles')
    
@endsection

@section('main')
<h2 style="text-align: center;margin-top:50px"><i class="fa-solid fa-users"></i> &nbsp; Users</h2>
<br>
<br>
            
<div class="row" style="padding: 10px">
    <a type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="btn btn-dark btn-icon-split" style="width: 12rem; margin:auto">
        <span class="icon text-white-50">
            <i class="fa-solid fa-user-plus" style="color: white"></i>
        </span>
        <span class="text"  style="width: 200px"> &nbsp; Create User</span>
    </a> 
</div>

<div class="row justify-content-center">

@livewire('create-user-modal')
@livewire('edit-user-modal')

<div style="width: 90%;margin:auto;justify-content:center">
    <table id="userTable" class="table table-bordered table-hover" style="width: 100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th style="text-align: center">Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
    
</div>

 
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js"></script>
    <script>
      
      $(document).ready(function() {
          $('#userslink').css('color', 'white');
        });

        $(document).ready(function() {
            $('#userTable').DataTable({
                "searching": true,
                "pageLength": 50,
                // order: [[1, 'asc']],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getusers') }}",
                    "type": "GET"
                },
                "columns": [
                    {
                        data: 'Firstname',
                        name: 'Firstname'
                    },
                    {
                        data: 'Lastname',
                        name: 'Lastname'
                    },
                    {
                        data: 'Username',
                        name: 'Username'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                                    return '<p style="text-align:center; height:10px"><a href="#" onclick="showEdit(' + data.UserID + ')">Edit</a> | <a href="#" onclick="showDelete(' + data.UserID + ')">Delete</a></p>';
                        }
                    },
                ]
            });
        });

        
        window.addEventListener('refresh-table', event => {
            $('#userTable').DataTable().ajax.reload();
        })

        window.addEventListener('close-create-modal', event => {
            $('#createUserModal').modal('hide');
        })

        function showEdit(id) {
                Livewire.dispatch('show-edit-modal', { id: id });
            }

        window.addEventListener('display-edit-modal', event => {
            $('#editUserModal').modal('show');
        })

        window.addEventListener('close-edit-modal', event => {
            $('#editUserModal').modal('hide');
        })

        window.addEventListener('show-message', event => {
                
                toastr.options = {
                    "progressBar" : true,
                    "closeButton" : true,
                }
                toastr.success(event.detail.message,'' , {timeOut:3000});
            })
    </script>
@endsection