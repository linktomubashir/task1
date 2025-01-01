@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'revenueForm', 'method' => 'GET']) }}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
                                {{ Form::date('start_date',  null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                                {{ Form::date('end_date',  null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::submit('Generate Report', ['class' => 'btn btn-primary mt-3', 'id' => 'generateReport']) }}
                    {{ Form::close() }}
                </div>
            </div>
            <table class="table table-striped mt-3" id="revenueTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Brand Name</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#generateReport').click(function(e) {
            e.preventDefault();

            var formData = $('#revenueForm').serialize();

            $.ajax({
                url: '{{ route('item-revenue.show') }}',
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('#revenueTable tbody').empty();
                    
                    if(response && response.length > 0) {
                        $('#revenueTable').show();
console.log(response);
                        response.forEach(function(item) {
                            var row = '<tr>';
                            row += '<td>' + item.brand_name + '</td>';
                            row += '<td>' + item.total_revenue  + '</td>';
                            row += '</tr>';
                            $('#revenueTable tbody').append(row);
                        });
                    } else {
                        $('#revenueTable tbody').append('<tr><td colspan="2" class="text-center">No data available for the selected criteria.</td></tr>');
                        $('#revenueTable').hide();
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "An unexpected error occurred. Please try again.";
                    toastr.error(errorMessage);
                }
            });
        });
    });
</script>
@endpush
