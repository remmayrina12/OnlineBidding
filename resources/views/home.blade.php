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
        color: #4b5563;
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
        background-color: #3b82f6;
        border-radius: 0.375rem;
        transition: background-color 0.3s;
    }

    .product-button:hover {
        background-color: #2563eb;
    }

    /* Submit button */
    .submit-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        color: #fff;
        background-color: #34d399;
        border-radius: 0.375rem;
        border: none;
        transition: background-color 0.3s;
    }

    .submit-button:hover {
        background-color: #059669;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        color: #4b5563;
    }

    .form-group input[type="number"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #d1d5db;
        font-size: 1rem;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s ease-in-out;
    }

    .form-group input[type="number"]:focus {
        border-color: #3b82f6;
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

    /* Modal image */
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
        color: #d32f2f;
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

<div class="category-container">
    <span class="category-label">Categories:</span>
    <div class="category-buttons">
        <a href="{{ route('home.show') }}" class="btn-category">All</a>
        <a href="{{ route('home.category', 'Corn') }}" class="btn-category">Corn</a>
        <a href="{{ route('home.category', 'Grains') }}" class="btn-category">Grains</a>
        <!-- Add other categories as needed -->
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6">Products List</h1>

            <!-- Product Grid -->
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card">
                        <strong>Created by:</strong> {{ $product->auctioneer->name ?? 'Unknown' }}
                        <div id="countdownTimer{{ $product->id }}" class="auction-timer" data-end-time="{{ strtotime($product->auction_time) }}">
                            Loading...
                        </div>

                        @if ($product->product_image)
                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product-image" />
                        @endif

                        <div class="product-title">{{ $product->product_name }}</div>

                        <div class="product-details">
                            <strong>Category:</strong> {{ $product->category }}<br />
                            <strong>Quantity:</strong> {{ $product->quantity }}<br />
                            <strong>Description:</strong> {{ $product->description }}<br />
                            <strong>Starting Price:</strong> {{ number_format($product->starting_price, 2) }}<br />

                            @if (!empty($highestBids[$product->id]))
                                <strong>Highest Bid:</strong> {{ $highestBids[$product->id]->amount }}<br />

                                @if(Auth::id() == $product->auctioneer_id)
                                    <strong>Bidder:</strong> {{ $highestBids[$product->id]->bidder->name }}<br />
                                @endif
                            @else
                                <strong>No bids for this product.</strong><br />
                            @endif
                            <br /><strong class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></strong>{{ $bidCounts[$product->id] ?? 0 }}<br />
                        </div>

                        <!-- Modal Trigger for Bidder and Winner -->
                        @if($product->auction_time > now())
                            @if(Auth::user()->role === "auctioneer" || Auth::user()->role === "admin")
                                <button type="button" class="btn btn-primary product-button" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                    View Product
                                </button>
                            @elseif(Auth::user()->role === "bidder")
                                <button type="button" class="btn btn-primary product-button" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                    Bid
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-primary product-button" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                View Winner
                            </button>
                        @endif
                    </div>

                    <!-- Product Modal -->
                    <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->product_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if($product->auction_time > now())
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

                                        <div id="modalCountdownTimer{{ $product->id }}" class="auction-timer d-flex justify-content-center align-items-center" style="font-size: 3rem;" data-end-time="{{ strtotime($product->auction_time) }}">
                                            Loading...
                                        </div>

                                        @if (!empty($product->product_image) && Storage::disk('public')->exists($product->product_image))
                                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="modal-product-img" />
                                        @else
                                            <p>No image available</p>
                                        @endif

                                        <p><strong class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></strong>{{ $bidCounts[$product->id] ?? 0 }}</p>
                                        <p><strong>Category:</strong> {{ $product->category }}</p>
                                        <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                                        <p><strong>Description:</strong> {{ $product->description }}</p>
                                        <p><strong>Starting Price:</strong> {{ number_format($product->starting_price, 2) }}</p>

                                        @if (!empty($highestBids[$product->id]))
                                            <p><strong>Highest Bid:</strong> {{ number_format($highestBids[$product->id]->amount, 2) }}</p>
                                            @if(Auth::id() == $product->auctioneer_id)
                                                <p><strong>Bidder:</strong> {{ $highestBids[$product->id]->bidder->name }}</p>
                                            @endif
                                        @else
                                            <p><strong>No bids for this product.</strong></p>
                                        @endif

                                        <p><strong>Created by:</strong> {{ $product->auctioneer->name ?? 'Unknown' }}</p>

                                        @if(Auth::user()->role == "bidder")
                                            @if(isset($alreadyBidOn[$product->id]))
                                                <div class="alert alert-info" role="alert">
                                                    {{ __('You have already placed a bid on this product.') }}
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('bidder.store') }}" class="w-100 p-3">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                                    <div class="form-group">
                                                        <label for="amount">{{ __('Bid Amount') }}</label>
                                                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" required class="form-control" />
                                                    </div>
                                                    <button type="submit" class="submit-button mt-3 w-100">{{ __('Place Bid') }}</button>
                                                </form>
                                            @endif
                                        @endif
                                    @else
                                        @if (!empty($highestBids[$product->id]))
                                            <p><strong>Congratulations to:</strong> {{ $highestBids[$product->id]->bidder->name }}</p>
                                        @else
                                            <p><strong>No bids for this product.</strong></p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/countdown.js') }}" defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

@endsection
