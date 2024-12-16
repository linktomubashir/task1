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
                    <p class="mb-0">Brand Management</p>

                    <a class="btn btn-sm btn-primary ms-auto" title="Creat brand"
                        data-url="{{ route('brands.create') }}" data-size="small" data-ajax-popup="true"
                        data-title="{{ __('Create New Brand') }}" data-bs-toggle="tooltip">
                        Add New
                    </a>
                </div>
                <div class="card-body">
                    <!-- Table for brands -->
                    <table class="table table-bordered table-striped" id="brand-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Items Count</th>
                                <th>Models Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamically populated by DataTables -->
                        </tbody>
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
            $('#brand-table').DataTable({
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
                    'url': '{{ route('brands.show') }}',  
                    
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'items', name: 'items' },
                    { data: 'models', name: 'models' },
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
        });

        // Delete Confirmation with SweetAlert
        function Delete(brandId) {
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
                    // AJAX call to delete the brand
                    $.ajax({
                        url: '{{ route('brands.destroy', '') }}' + '/' + brandId,
                        method: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(result) {
                            swalWithBootstrapButtons.fire({
                                title: "Deleted!",
                                text: "Brand has been deleted.",
                                icon: "success",
                                timer: 2000
                            });
                          table.ajax.reload();
                        },
                        error: function(jqXHR, exception) {
                            toastr.error('Failed to delete brand');
                        }
                    });
                }
            });
        }
    </script>
@endpush
