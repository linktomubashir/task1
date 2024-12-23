@extends('layouts.app')

@push('header')
    <!-- Include DataTables styles -->
    <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <p class="mb-0">Users</p>
                    <div class="d-flex align-items-end ms-auto">
                        <a class="btn btn-sm btn-primary" title="Creat Models" data-url="{{ route('users.create') }}"
                            data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Model') }}"
                            data-bs-toggle="tooltip">
                            Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table for models -->
                    <table class="table table-bordered table-striped" id="model-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include DataTables scripts -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#model-table').DataTable({
                'paging': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                'responsive': true,
                'ajax': {
                    'url': '{{ route('user.show') }}',
                    'data': function(d) {
                        d.show_trashed = $('#showTrashed').prop('checked');
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                    }
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }

            });

            $('#showTrashed').change(function() {
                table.draw();
            });

            window.handleAction = function(itemId, actionType) {
                let title, text, icon, url, method, successMessage;

                switch (actionType) {
                    case 'delete':
                        title = "Are you sure?";
                        text = "This action will move the item to trash.";
                        icon = "warning";
                        url = '{{ route('user.destroy', '') }}';
                        method = 'GET';
                        successMessage = "Item has been moved to trash..";
                        break;
                }

                swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success py-2 px-4",
                        cancelButton: "btn btn-danger mx-4 py-2 px-4"
                    },
                    buttonsStyling: false
                }).fire({
                    title,
                    text,
                    icon,
                    showCancelButton: true,
                    confirmButtonText: "Yes, proceed!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `${url}/${itemId}`,
                            method: method,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                swal.fire({
                                    title: successMessage,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                                table.ajax.reload();
                            },
                            error: function() {
                                toastr.error('Action failed');
                            }
                        });
                    }
                });
            };
        });
    </script>
@endpush
