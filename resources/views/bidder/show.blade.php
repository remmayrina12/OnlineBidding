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
        max-width: 50%;
        max-height: 100px;
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

    .modal-product-img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 8px;
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

    /* Container for category label and buttons */
    .category-container {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    /* Styling for the "Categories" label */
    .category-label {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-right: 15px; /* Space between label and buttons */
    }

    /* Container for buttons to align them horizontally */
    .category-buttons {
        display: flex;
        gap: 10px; /* Space between each button */
    }

    /* Style for category buttons */
    .btn-category {
        display: inline-block;
        background-color: #007bff; /* Primary blue color */
        color: #fff; /* White text */
        padding: 10px 20px; /* Padding inside buttons */
        border-radius: 5px; /* Rounded corners */
        text-decoration: none; /* Remove underline */
        font-weight: bold; /* Bold text */
        transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition */
    }

    /* Hover effect */
    .btn-category:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: scale(1.05); /* Slightly larger on hover */
    }

    /* Focus effect for accessibility */
    .btn-category:focus {
        outline: 2px solid #0056b3; /* Outline for focus */
        outline-offset: 2px;
    }
</style>
<!-- Search Form -->
<form action="{{ route('bidder.show') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="query" class="form-control" placeholder="Search for products..." value="{{ request('query') }}">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<div class="category-container">
    <span class="category-label">Categories:</span>
    <div class="category-buttons">
        <a href="{{ route('bidder.show') }}" class="btn-category">All</a>
        <a href="{{ route('bidder.category', 'Corn') }}" class="btn-category">Corn</a>
        <a href="{{ route('bidder.category', 'Grains') }}" class="btn-category">Grains</a>
        <!-- Add other categories as needed -->
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6">On going auction you bid on.</h1>

            <!-- Product Grid -->
            <div class="product-grid">
                @foreach ($bids as $bid)

                @if ($bid->product->auction_status == 'open' && $bid->product->auction_time > now())

                <div class="product-card">
                    <strong>Created by:</strong> {{ $bid->product->auctioneer->name ?? 'Unknown' }}
                    <div id="countdownTimer{{ $bid->product->id }}" class="auction-timer" data-end-time="{{ strtotime($bid->product->auction_time) }}" data-auction-status="{{ $bid->product->auction_status }}">
                        Loading...
                    </div>

                    @if ($bid->product->product_image)
                        <img src="{{ asset('storage/' . $bid->product->product_image) }}" alt="{{ $bid->product->product_name }}" class="product-image" />
                    @endif

                    <div class="product-title">{{ $bid->product->product_name }}</div>

                    <div class="product-details">
                        <strong>Category:</strong> {{ $bid->product->category }}<br />
                        <strong>Quantity:</strong> {{ number_format($bid->product->quantity) }} kg<br />
                        <strong>Description:</strong> {{ $bid->product->description }}<br />
                        <strong>Starting Price:</strong> ₱{{ number_format($bid->product->starting_price, 2) }}<br />

                        @if (!empty($highestBids[$bid->product->id]))
                            <strong>Highest Bid:</strong> ₱{{ number_format($highestBids[$bid->product->id]->amount, 2) }}<br />

                            @if(Auth::id() == $bid->product->auctioneer_id)
                                <p><strong>Bidder:</strong> {{ $highestBids[$bid->product->id]->bidder->name }}</p>
                            @endif
                        @else
                            <strong>No bids for this product.</strong><br />
                        @endif

                        <br /><strong class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></strong>{{ $bidCounts[$bid->product->id] ?? 0 }}<br />
                    </div>

                    <!-- Bid Button for Bidder -->
                    <button type="button" class="btn btn-primary product-button" data-bs-toggle="modal" data-bs-target="#productModal{{ $bid->product->id }}">
                        Bid
                    </button>
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

                                    <!-- Timer -->
                                    <div id="modalCountdownTimer{{ $bid->product->id }}" class="auction-timer d-flex justify-content-center align-items-center" style="font-size: 3rem;" data-end-time="{{ strtotime($bid->product->auction_time) }}" data-auction-status="{{ $bid->product->auction_status }}">
                                        Loading...
                                    </div>

                                    <!-- Product Image -->
                                    @if (!empty($bid->product->product_image) && Storage::disk('public')->exists($bid->product->product_image))
                                        <img src="{{ asset('storage/' . $bid->product->product_image) }}" alt="{{ $bid->product->product_name }}" class="modal-product-img" />
                                    @else
                                        <p>No image available</p>
                                    @endif

                                    <!-- Product Details -->
                                    <p><strong class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></strong>{{ $bidCounts[$bid->product->id] ?? 0 }}</p>
                                    <p><strong>Category:</strong> {{ $bid->product->category }}</p>
                                    <p><strong>Quantity:</strong> {{ number_format($bid->product->quantity) }} kg</p>
                                    <p><strong>Description:</strong> {{ $bid->product->description }}</p>
                                    <p><strong>Starting Price:</strong> ₱{{ number_format($bid->product->starting_price, 2) }}</p>

                                    @if (!empty($highestBids[$bid->product->id]))
                                        <p><strong>Highest Bid:</strong> ₱{{ number_format($highestBids[$bid->product->id]->amount, 2) }}</p>
                                    @endif

                                    <p>
                                        <strong>Created by:</strong>
                                            <a href="{{ route('profile.show', $bid->product->auctioneer->id ?? '') }}">
                                                {{ $bid->product->auctioneer->name ?? 'Unknown' }}
                                            </a>
                                    </p>

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
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

@endsection
