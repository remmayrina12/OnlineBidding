@extends('layouts.app')

@section('content')

<style>
    /* General Layout */
    .form-container {
        background-color: #fff;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 2rem;
        font-weight: 600;
        text-align: center;
        color: #1f2937; /* Dark Gray */
        margin-bottom: 1.5rem;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        font-size: 0.875rem;
        color: #374151; /* Cool Gray */
        margin-bottom: 0.5rem;
    }

    input, textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #1f2937;
        background-color: #f9fafb; /* Light Gray */
        border: 1px solid #d1d5db; /* Border Gray */
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus, textarea:focus {
        border-color: #6366f1; /* Indigo Focus */
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    /* Button */
    .submit-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        color: #fff;
        background-color: #3b82f6; /* Blue */
        border-radius: 0.5rem;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
    }

    .submit-button:hover {
        background-color: #2563eb; /* Darker Blue */
        transform: translateY(-2px);
    }

    .submit-button:active {
        background-color: #1d4ed8; /* Even Darker Blue */
        transform: translateY(0);
    }

    /* Media Queries for responsiveness */
    @media (min-width: 640px) {
        .form-container {
            padding: 3rem;
        }
    }
</style>

<div class="py-12">
    <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <div class="form-container">
            <h2>{{ __('Create Product') }}</h2>

            <form method="POST" action="{{ route('auctioneer.store') }}" enctype="multipart/form-data">
                @csrf
                <!-- Product Name -->
                <div class="form-group">
                    <label for="product_name">{{ __('Product Name') }}</label>
                    <input type="text" name="product_name" id="product_name" required>
                </div>

                <!-- Class -->
                <div class="form-group">
                    <label for="product_class">{{ __('Class') }}</label>
                    <input type="text" name="product_class" id="product_class" required>
                </div>

                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity">{{ __('Quantity') }}</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea name="description" id="description" rows="3" required></textarea>
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label for="product_image">{{ __('Product Image') }}</label>
                    <input type="file" name="product_image" id="product_image" accept="image/*">
                </div>

                <!-- Starting Price -->
                <div class="form-group">
                    <label for="starting_price">{{ __('Starting Price') }}</label>
                    <input type="number" step="0.01" name="starting_price" id="starting_price" required>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit" class="submit-button">
                        {{ __('Create Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
