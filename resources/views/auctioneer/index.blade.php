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

                <!-- Check if there are any products -->
                @if ($products->isEmpty())
                    <p>{{ __("You haven't created any products yet.") }}</p>
                @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Product Class</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Starting Price</th>
                            <th>Auction Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    @if($product->product_image)
                                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid rounded" style="width: 80px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->product_class }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->starting_price }}</td>
                                <td><span id="timer-{{ $product->id }}"></span></td>
                                <td>
                                    <button class="btn btn-link text-primary" data-bs-toggle="modal" data-bs-target="#productModal-{{ $product->id }}">{{ __('View for Bidding') }}</button>
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

@foreach ($products as $product)
    <div id="productModal-{{ $product->id }}" class="modal fade" tabindex="-1" aria-labelledby="modalTitle-{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">{{ $product->product_name }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-6 text-center modal-image">
                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid rounded">
                        </div>
                        <!-- Product Details -->
                        <div class="col-md-6">
                            <p><strong>Product Name:</strong> {{ $product->product_name }}</p>
                            <p><strong>Product Class:</strong> {{ $product->product_class }}</p>
                            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                            <p><strong>Description:</strong> {{ $product->description }}</p>
                            <p><strong>Starting Price:</strong> {{ $product->starting_price }}</p>
                            <p><strong>Auction Time:</strong> {{ $product->auction_time }}</p>
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer justify-content-between">
                    <!-- Edit Form -->
                    <form action="{{ route('auctioneer.edit', $product->id) }}" method="GET" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>
                    <!-- Delete Form -->
                    <form action="{{ route('auctioneer.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- JavaScript Code -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($products as $product)
            if ("{{ $product->auction_time }}") {
                let endTime_{{ $product->id }} = new Date("{{ $product->auction_time }}").getTime();
                let countdownElement_{{ $product->id }} = document.getElementById("timer-{{ $product->id }}");

                function updateTimer_{{ $product->id }}() {
                    let now = new Date().getTime();
                    let timeLeft = endTime_{{ $product->id }} - now;

                    if (timeLeft > 0) {
                        let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                        countdownElement_{{ $product->id }}.innerHTML = (hours > 0 ? hours + "h " : "") + minutes + "m " + seconds + "s";
                    } else {
                        countdownElement_{{ $product->id }}.innerHTML = "Auction ended";
                        clearInterval(timer_{{ $product->id }});
                    }
                }

                let timer_{{ $product->id }} = setInterval(updateTimer_{{ $product->id }}, 1000);
            }
        @endforeach
    });
</script>
@endsection
