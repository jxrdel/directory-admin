@extends('layout')

@section('styles')
    <style>
        table.dataTable thead {
            background-color: rgba(14, 153, 240, 0.1)
        }

        table.dataTable tr.dtrg-group th {
            background-color: rgba(60, 64, 66, 0.8);
            text-align: left;
            color: white
        }

        table.dataTable tr.dtrg-group.dtrg-level-0 th {
            font-weight: bold
        }

        table.dataTable tr.dtrg-group.dtrg-level-1 th,
        table.dataTable tr.dtrg-group.dtrg-level-2 th,
        table.dataTable tr.dtrg-group.dtrg-level-3 th,
        table.dataTable tr.dtrg-group.dtrg-level-4 th,
        table.dataTable tr.dtrg-group.dtrg-level-5 th {
            background-color: rgba(0, 0, 0, 0.05);
            padding-top: .25em;
            padding-bottom: .25em;
            padding-left: 2em;
            font-size: .9em
        }

        table.dataTable tr.dtrg-group.dtrg-level-2 th {
            background-color: rgba(0, 0, 0, 0.01);
            padding-left: 2.5em
        }

        table.dataTable tr.dtrg-group.dtrg-level-3 th {
            background-color: rgba(0, 0, 0, 0.01);
            padding-left: 3em
        }

        table.dataTable tr.dtrg-group.dtrg-level-4 th {
            background-color: rgba(0, 0, 0, 0.01);
            padding-left: 3.5em
        }

        table.dataTable tr.dtrg-group.dtrg-level-5 th {
            background-color: rgba(0, 0, 0, 0.01);
            padding-left: 4em
        }

        html.dark table.dataTable tr.dtrg-group th {
            background-color: rgba(255, 255, 255, 0.1)
        }

        html.dark table.dataTable tr.dtrg-group.dtrg-level-1 th {
            background-color: rgba(255, 255, 255, 0.05)
        }

        html.dark table.dataTable tr.dtrg-group.dtrg-level-2 th,
        html.dark table.dataTable tr.dtrg-group.dtrg-level-3 th,
        html.dark table.dataTable tr.dtrg-group.dtrg-level-4 th,
        html.dark table.dataTable tr.dtrg-group.dtrg-level-5 th {
            background-color: rgba(255, 255, 255, 0.01)
        }

        table.dataTable.table-striped tr.dtrg-level-0 {
            background-color: rgba(0, 0, 0, 0.1)
        }

        table.dataTable.table-striped tr.dtrg-level-1 {
            background-color: rgba(255, 0, 0, 0.05)
        }

        table.dataTable.table-striped tr.dtrg-level-2,
        table.dataTable.table-striped tr.dtrg-level-3,
        table.dataTable.table-striped tr.dtrg-level-4,
        table.dataTable.table-striped tr.dtrg-level-5 {
            background-color: rgba(255, 0, 0, 0.01)
        }

        table.dataTable.table-striped tr.dtrg-level-1 tr.dtrg-level-2 th,
        table.dataTable.table-striped tr.dtrg-level-3 th,
        table.dataTable.table-striped tr.dtrg-level-4 th,
        table.dataTable.table-striped tr.dtrg-level-5 th {
            background-color: transparent
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>
@endsection

@section('main')
    <h2 style="text-align: center;margin-top:50px"><i class="fa-solid fa-book"></i> &nbsp; Directory</h2>
    <br>
    <br>
    <div class="row justify-content-center">

        @livewire('create-record')
        @livewire('edit-record')
        @livewire('delete-record-modal')


        <div class="row" style="padding: 10px">
            <a type="button" data-bs-toggle="modal" data-bs-target="#createRecordModal" class="btn btn-dark btn-icon-split"
                style="width: 12rem; margin:auto">
                <span class="icon text-white-50">
                    <i class="fa-solid fa-plus" style="color: white"></i>
                </span>
                <span class="text" style="width: 200px"> &nbsp; Add Record</span>
            </a>
        </div>

        <div style="width: 100%;margin:auto">
            <table id="directoryTable" class="table table-bordered table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Unit</th>
                        <th>Position</th>
                        <th>Extension</th>
                        <th>Floor</th>
                        <th style="width: 10%">Actions</th>
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
            $('#directorylink').css('color', 'white');
        });

        $(document).ready(function() {
            $('#directoryTable').DataTable({
                "searching": true,
                "pageLength": 50,
                order: [
                    [1, 'asc']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getdirectory') }}",
                    "type": "GET"
                },
                "rowGroup": {
                    endRender: null,
                    startRender: function(rows, group) {
                        return group + ' (' + rows.count() + ')';
                    },
                    dataSrc: "department"
                },
                "columns": [{
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'groupname',
                        name: 'groupname'
                    },
                    {
                        data: 'extname',
                        name: 'extname'
                    },
                    {
                        data: 'extno',
                        name: 'extno'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<p style="text-align:center; height:10px"><a href="javascript:void(0);" onclick="showEdit(' +
                                data.id +
                                ')"><i class="fa-solid fa-pen-to-square"></i></a> | <a href="javascript:void(0);" onclick="showDelete(' +
                                data.id + ')"><i class="bi bi-trash-fill text-danger"></i></a></p>';
                        }
                    },
                ]
            });
        });


        window.addEventListener('refresh-table', event => {
            $('#directoryTable').DataTable().ajax.reload();
        })

        window.addEventListener('close-create-modal', event => {
            $('#createRecordModal').modal('hide');
        })

        function showEdit(id) {
            Livewire.dispatch('show-edit-modal', {
                id: id
            });
        }

        window.addEventListener('display-edit-modal', event => {
            $('#editRecordModal').modal('show');
        })

        window.addEventListener('close-edit-modal', event => {
            $('#editRecordModal').modal('hide');
        })

        function showDelete(id) {
            Livewire.dispatch('show-delete-modal', {
                model: 'Directory',
                id: id
            });
        }

        window.addEventListener('display-delete-modal', event => {
            $('#deleteRecordModal').modal('show');
        })

        window.addEventListener('close-delete-modal', event => {
            $('#deleteRecordModal').modal('hide');
        })

        window.addEventListener('show-message', event => {

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success(event.detail.message, '', {
                timeOut: 3000
            });
        })
    </script>
@endsection
