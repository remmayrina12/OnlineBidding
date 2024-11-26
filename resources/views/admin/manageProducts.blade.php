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
@if(session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if(session('failed'))
<script>
    Swal.fire({
        title: 'Rejected!',
        text: "{{ session('failed') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-4">{{ __("List of Auctions!") }}</h3>
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
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Starting Price</th>
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
                                <td>{{ $manage->category }}</td>
                                <td>{{ $manage->quantity }}</td>
                                <td>{{ $manage->description }}</td>
                                <td>{{ $manage->starting_price }}</td>
                                <td>{{ $manage->product_post_status }}</td>
                                <td>
                                    <a href="{{route('admin.acceptProduct', $manage->id)}}" class="btn btn-outline-secondary" onclick="return confirm('Are you sure you want to accept this product?')">Accept</a>
                                </td>
                                <td>
                                    <a href="{{route('admin.rejectProduct', $manage->id)}}" class="btn btn-outline-primary" onclick="return confirm('Are you sure you want to reject this product?')">Reject</a>
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
@endsection
