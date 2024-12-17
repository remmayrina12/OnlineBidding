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

    input, textarea, select {
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

    input:focus, textarea:focus, select:focus {
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
</style>

<div class="py-12">
    <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <div class="form-container">
            <h2>{{ __('Edit Product') }}</h2>

            <form method="POST" action="{{ route('auctioneer.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Category -->
                <div class="form-group mb-3">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="Corn" {{ old('category', $product->category) == 'Corn' ? 'selected' : '' }}>{{ __('Corns') }}</option>
                        <option value="Grains" {{ old('category', $product->category) == 'Grains' ? 'selected' : '' }}>{{ __('Grains') }}</option>
                    </select>
                </div>

                <!-- Product Name -->
                <div class="form-group mb-3">
                    <label for="product_name" class="form-label">{{ __('Product Name') }}</label>
                    <select name="product_name" id="product_name" class="form-select" required>
                        <!-- Options populated dynamically using JavaScript -->
                    </select>
                </div>

                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity">{{ __('Quantity (Per kg)') }}</label>
                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        value="{{ old('quantity', $product->quantity) }}"
                        required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        required>{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label for="product_image">{{ __('Product Image') }}</label>
                    @if($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image" class="img-thumbnail mt-2" width="150">
                    @endif
                    <input
                        type="file"
                        name="product_image"
                        id="product_image"
                        accept="image/*">
                </div>

                <!-- Starting Price -->
                <div class="form-group">
                    <label for="starting_price">{{ __('Starting Price (PHP)') }}</label>
                    <input
                        type="number"
                        step="0.01"
                        name="starting_price"
                        id="starting_price"
                        value="{{ old('starting_price', $product->starting_price) }}"
                        required>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit" class="submit-button" onclick="return confirm('Are you sure you want to update this product?')">
                        {{ __('Update Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryElement = document.getElementById('category');
        const productNameElement = document.getElementById('product_name');

        const productsByCategory = {
            Corn: ['NK6130', 'NK6410', 'NK6410 VIP', 'NK6414', 'NK6505', 'NK8840', 'NK8840 VIP', 'DK6919S', 'DK8131S', 'DK8899S', 'DK8282S', 'DK9118S'],
            Grains: ['NK5017', 'RH 9000', 'S6003']
        };

        function populateProductNames() {
            const selectedCategory = categoryElement.value;
            const products = productsByCategory[selectedCategory] || [];
            productNameElement.innerHTML = '';

            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product;
                option.textContent = product;
                option.selected = product === "{{ old('product_name', $product->product_name) }}";
                productNameElement.appendChild(option);
            });
        }

        categoryElement.addEventListener('change', populateProductNames);

        // Trigger change event on page load to populate options
        populateProductNames();
    });
</script>
@endsection
