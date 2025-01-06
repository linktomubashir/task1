@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Items Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="itemsAccordion">
                            @foreach($brands as $brand)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $brand->id }}">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $brand->id }}" 
                                                aria-expanded="false" 
                                                aria-controls="collapse{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $brand->id }}" 
                                         class="accordion-collapse collapse" 
                                         aria-labelledby="heading{{ $brand->id }}" 
                                         data-bs-parent="#itemsAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Item Name</th>
                                                            <th>Total Quantity Sold</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($soldItems[$brand->id] as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $item['item_name'] }}</td>
                                                                <td>{{ $item['total_quantity'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
