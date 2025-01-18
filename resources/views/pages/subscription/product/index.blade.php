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
                <div class="card-header d-flex">
                    <p class="mb-0">Products</p>
                    <div class="d-flex align-items-end ms-auto">
                        <div class="form-check form-switch me-3">
                            <label class="form-check-label" for="showTrashed">Show Archived</label>
                            <input class="form-check-input" type="checkbox" id="showTrashed" />
                        </div>
                        <a class="btn btn-sm btn-primary" title="Create product" data-url="{{ route('product.create') }}"
                            data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Product') }}"
                            data-bs-toggle="tooltip">
                            Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="product-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Decription</th>
                                <th>Price</th>
                                <th>Currency</th>
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
            let table = $('#product-table').DataTable({
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
                    'url': '{{ route('products.show') }}', 
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
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'currency',
                        name: 'currency'
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
                        text = "This action will delete this product.";
                        icon = "warning";
                        url = '{{ route('products.destroy', '') }}';
                        method = 'GET'
                        successMessage = "Product has been deleted.";
                        break;
                        case 'restore':
                        title = "Are you sure?";
                        text = "You want to restore this brand!";
                        icon = "info";
                        url = '{{ route('products.restore', '') }}';
                        method = 'POST';
                        successMessage = "Brand has been restored.";
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
