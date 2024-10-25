@extends('layouts.app')

@section('content')

<style>
    /* Modal background overlay */
    .modal-content {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 100%;
        transition: all 0.3s ease-in-out;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
    }

    .modal-details {
        font-size: 1rem;
        color: #4a5568;
        text-align: left;
        margin: 1rem 0;
    }

    .modal-image img {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
    }

    .modal-actions {
        display: flex;
        justify-content: space-around;
        margin-top: 1.5rem;
    }
</style>

<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-4">{{ __("List of Auctions!") }}</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('failed'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('failed') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Check if there are any products -->
                @if ($manages->isEmpty())
                    <p>{{ __("Auctioneer hasn't created any products yet.") }}</p>
                @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Owner Name</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Product Class</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Starting Price</th>
                            <th>Auction Time</th>
                            <th>Status</th>
                            <th>Accept</th>
                            <th>Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($manages as $manage)
                            <tr>
                                <td>{{ $manage->auctioneer->name }}</td>
                                <td>
                                    @if($manage->product_image)
                                        <img src="{{ asset('storage/' . $manage->product_image) }}" alt="{{ $manage->product_name }}" class="img-fluid rounded" style="width: 80px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $manage->product_name }}</td>
                                <td>{{ $manage->product_class }}</td>
                                <td>{{ $manage->quantity }}</td>
                                <td>{{ $manage->description }}</td>
                                <td>{{ $manage->starting_price }}</td>
                                <td id="countdownTimer{{ $manage->id }}" class="auction-timer" data-end-time="{{ strtotime($manage->auction_time) }}"></td>
                                <td>{{ $manage->product_post_status }}</td>
                                <td>
                                    <a href="{{route('admin.acceptProduct', $manage->id)}}" class="btn btn-outline-secondary">Accept</a>
                                </td>
                                <td>
                                    <a href="{{route('admin.rejectProduct', $manage->id)}}" class="btn btn-outline-primary">Reject</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/countdown.js') }}" defer></script>

@endsection
