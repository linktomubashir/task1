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
                                        @if($item->getEffectivePrice() < $item->amount)  
                                        @php
                                            // Calculate discount percentage
                                            $discountPercentage = round(((($item->amount - $item->getEffectivePrice()) / $item->amount) * 100), 2);
                                        @endphp
                                        <p class="card-text">
                                            <del class="danger">Price: ${{ $item->amount ?? '' }}</del>  
                                        </p>
                                        <p class="card-text">
                                            Discount Price: ${{ $item->getEffectivePrice() ?? '' }} 
                                        </p>
                                        <p class="card-text">
                                            <span class="text-success">Save {{ $discountPercentage }}%</span>
                                        </p>
                                    @else
                                        <p class="card-text">
                                            Price: ${{ $item->amount ?? '' }}  
                                        </p>
                                    @endif
                                        <p>Model: {{ $item->models->name ?? '' }}</p>
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
