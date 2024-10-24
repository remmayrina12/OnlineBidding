@extends('layouts.app')
@section('content')

<style>
    /* Container for the grid of products */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    /* Each product card */
    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
    }

    /* Product title */
    .product-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* Product details */
    .product-details {
        margin-bottom: 15px;
        font-size: 0.875rem;
        color: #4b5563; /* Cool Gray */
    }

    /* Product image */
    .product-image {
        max-width: 100%;
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    /* Hover effect for the card */
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    /* Button */
    .product-button {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        color: #fff;
        background-color: #3b82f6; /* Blue */
        border-radius: 0.375rem;
        transition: background-color 0.3s;
    }

    .product-button:hover {
        background-color: #2563eb; /* Darker Blue */
    }

    /* Submit button */
    .submit-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        color: #fff;
        background-color: #34d399; /* Green */
        border-radius: 0.375rem;
        border: none;
        transition: background-color 0.3s;
    }

    .submit-button:hover {
        background-color: #059669; /* Darker Green */
    }

    /* Form styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        color: #4b5563; /* Cool Gray */
    }

    .form-group input[type="number"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #d1d5db; /* Gray border */
        font-size: 1rem;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s ease-in-out;
    }

    .form-group input[type="number"]:focus {
        border-color: #3b82f6; /* Blue focus */
        outline: none;
    }

    /* Modal header */
    .modal-header {
        border-bottom: none;
    }

    /* Modal body */
    .modal-body {
        padding: 20px;
    }

    /* Media Queries */
    @media (min-width: 640px) {
        .product-card {
            padding: 30px;
        }
    }

    .auction-timer {
        font-size: 1.2rem;
        font-weight: bold;
        color: #d32f2f; /* Red color for countdown */
        margin-top: 10px;
    }

</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6">Your Bids</h1>

            <!-- Product Grid -->
            <div class="product-grid">
                @foreach ($bids as $bid)
                <div class="product-card">

                    <!-- Timer -->
                    <div id="countdownTimer{{ $bid->product->id }}" class="auction-timer" data-end-time="{{ strtotime($bid->product->auction_time) }}">
                        Loading...
                    </div>

                    <!-- Product Image -->
                    @if ($bid->product->product_image)
                    <img src="{{ asset('storage/' . $bid->product->product_image) }}" alt="{{ $bid->product->product_name }}" class="product-image" />
                    @endif

                    <!-- Product Title -->
                    <div class="product-title">{{ $bid->product->product_name }}</div>

                    <!-- Product Details -->
                    <div class="product-details">
                        <strong>Class:</strong> {{ $bid->product->product_class }}<br />
                        <strong>Quantity:</strong> {{ $bid->product->quantity }}<br />
                        <strong>Description:</strong> {{ $bid->product->description }}<br />
                        <strong>Starting Price:</strong> ${{ number_format($bid->product->starting_price, 2) }}<br />

                        <!-- Show highest bid -->
                        @if (!empty($highestBids[$bid->product->id]))
                        <strong>Highest Bid:</strong> {{ $highestBids[$bid->product->id]->amount }}<br />
                        @endif

                        <strong>Created by:</strong> {{ $bid->product->auctioneer->name ?? 'Unknown' }}
                    </div>

                    <!-- Bid Button for Bidder -->
                    @if(Auth::user()->role == "bidder")
                    <button type="button" class="btn btn-primary product-button" data-bs-toggle="modal" data-bs-target="#productModal{{ $bid->product->id }}">
                        Bid
                    </button>
                    @endif
                </div>

                <!-- Modal -->
                <div class="modal fade" id="productModal{{ $bid->product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $bid->product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel{{ $bid->product->id }}">{{ $bid->product->product_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
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

                                <!-- Timer -->
                                <div id="modalCountdownTimer{{ $bid->product->id }}" class="auction-timer d-flex justify-content-center align-items-center " style="font-size: 3rem;" data-end-time="{{ strtotime($bid->product->auction_time) }}">
                                    Loading...
                                </div>


                                <!-- Product Image -->
                                @if (!empty($bid->product->product_image) && Storage::disk('public')->exists($bid->product->product_image))
                                <img src="{{ asset('storage/' . $bid->product->product_image) }}" alt="{{ $bid->product->product_name }}" class="img-fluid mb-3" />
                                @else
                                <p>No image available</p>
                                @endif
                                <!-- Product Details -->
                                <p><strong>Class:</strong> {{ $bid->product->product_class }}</p>
                                <p><strong>Quantity:</strong> {{ $bid->product->quantity }}</p>
                                <p><strong>Description:</strong> {{ $bid->product->description }}</p>
                                <p><strong>Starting Price:</strong> ${{ $bid->product->starting_price }}</p>

                                @if (!empty($highestBids[$bid->product->id]))
                                <p><strong>Highest Bid:</strong> {{$highestBids[$bid->product->id]->amount }}</p>
                                @endif

                                <p><strong>Created by:</strong> {{ $bid->product->auctioneer->name ?? 'Unknown' }}</p>
                            </div>

                            <!-- Bid Form -->
                            <form method="POST" action="{{ route('bidder.update', $bid->product->id) }}" class="w-100 p-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="product_id" value="{{ $bid->product->id }}" />
                                <div class="form-group">
                                    <label for="amount">{{ __('Bid Amount') }}</label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required class="form-control" />
                                </div>
                                <button type="submit" class="submit-button mt-3 w-100">{{ __('Place Bid') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/countdown.js') }}" defer></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />


@endsection
