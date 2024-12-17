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
                    <p class="mb-0">Item page</p>

                    <a class="btn btn-sm btn-primary ms-auto" title="Creat item" data-url="{{ route('item.create') }}"
                        data-size="small" data-ajax-popup="true" data-title="{{ __('Create New Item') }}"
                        data-bs-toggle="tooltip">
                        Add New
                    </a>
                </div>
                <div class="card-body">
                    <!-- Table for items -->
                    <table class="table table-bordered table-striped" id="item-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Date Added</th>
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
            let table = $('#item-table').DataTable({
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
                    'url': '{{ route('item.show') }}', // Adjust the route for your blogs' server-side processing
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
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
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
            window.Delete = function(rowId) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success py-2 px-4",
                        cancelButton: "btn btn-danger mx-4 py-2 px-4"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true,

                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX call
                        $.ajax({
                            url: '{{ url('/item/destroy') }}' + '/' + rowId,
                            method: 'get',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                swalWithBootstrapButtons.fire({
                                    title: "Success!",
                                    text: "Slider Deletd successfully.",
                                    icon: "success",
                                    timer: 2000
                                });
                                table.ajax.reload();
                            },
                            error: function(jqXHR, exception) {
                                toastr.error('Failed to update data');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush
