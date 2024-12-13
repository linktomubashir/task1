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
                    <p class="mb-0">Models Page</p>

                    <a class="btn btn-sm btn-primary ms-auto" title="Creat Models"
                        data-url="{{ route('models.create') }}" data-size="small" data-ajax-popup="true"
                        data-title="{{ __('Create New Model') }}" data-bs-toggle="tooltip">
                        Add New
                    </a>
                </div>
                <div class="card-body">
                    <!-- Table for models -->
                    <table class="table table-bordered table-striped" id="model-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Items Count</th>
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
        var table = $('#model-table').DataTable({
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
                'url': '{{ route('models.show') }}', // Adjust the route for your models' server-side processing
            },
            columns: [
                {
                    data: 'id', 
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'brand_name', // this is the name of the brand
                    name: 'brand_name'
                },
                {
                    data: 'items',
                    name: 'items'
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

        // Delete function with confirmation
        function Delete(rowId) {
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
                    // AJAX call to delete model and associated items
                    $.ajax({
                        url: '{{ url('/models/destroy') }}' + '/' + rowId,
                        method: 'get',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(result) {
                            swalWithBootstrapButtons.fire({
                                title: "Success!",
                                text: "Model and associated items deleted successfully.",
                                icon: "success",
                                timer: 2000
                            });
                            window.location.reload();
                        },
                        error: function(jqXHR, exception) {
                            toastr.error('Failed to delete data');
                        }
                    });
                }
            });
        }
    </script>
@endpush