@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <p class="mb-0">{{ $brand->name }}</p>
                </div>
                <div class="card-body">
                    <div class="row ">
                        @foreach ($items as $item)
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h3>{{ $item->name }}</h3>
                                        <p class="card-text">Price: ${{ $item->amount }}</p>
                                        <p>Model: {{ $item->models->name }}</p>
                                        <a href="{{ route('service.purchase', $item->id) }}"
                                            class="btn btn-sm btn-primary">Purchase</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
