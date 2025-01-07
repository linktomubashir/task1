@extends('layouts.app')

@push('header')
    <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <p class="mb-0">Audit Log Management</p>
                    <div class="d-flex align-items-center ms-auto">
                        <div class="mx-3" style="width: 180px;">
                            {{ Form::select('user', $users->pluck('name', 'id'), null, ['id' => 'user_id', 'class' => 'form-control select2', 'multiple', 'data-placeholder' => 'Please select user']) }}
                        </div>
                        <div class="mx-3" style="width: 180px;">
                            {{ Form::select('model', $models, null, ['id' => 'filter-model', 'class' => 'form-control select2', 'multiple', 'data-placeholder' => 'Please select model']) }}
                        </div>
                        <div class="mx-3" style="width: 180px;">
                            {{ Form::text('date_range', null, ['id' => 'filter-dates', 'class' => 'form-control date-range-picker', 'placeholder' => 'Select date range']) }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="audit-log-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Action</th>
                                <th>Model</th>
                                <th>Model ID</th>
                                <th>User</th>
                                <th>Changes</th>
                                <th>Performed At</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#audit-log-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                responsive: true,
                ajax: {
                    url: '{{ route('audit.logs') }}',
                    data: function(d) {
                        d.display = 'on',
                            d.user_id = $('#user_id').val();
                        d.model = $('#filter-model').val();
                        d.date_range = $('#filter-dates').val();
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'model_type',
                        name: 'model_type'
                    },
                    {
                        data: 'model_id',
                        name: 'model_id'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'changes',
                        name: 'changes',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'performed_at',
                        name: 'performed_at'
                    },
                ],
            });

            $('.select2').select2({
                width: '100%'
            });

            $('#user_id, #filter-model').change(function() {
                table.draw();
            });

            $('#filter-dates').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false,
                opens: 'left',
            });

            $('#filter-dates').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(
                    `${picker.startDate.format('YYYY-MM-DD')} - ${picker.endDate.format('YYYY-MM-DD')}`);
                    table.draw();
            });

            $('#filter-dates').on('cancel.daterangepicker', function() {
                $(this).val('');
            });
        });
    </script>
@endpush
