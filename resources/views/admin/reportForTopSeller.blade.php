@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Top Sellers Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reportForTopSeller.getTopSellers') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="month">Filter by Month:</label>
                <input type="month" name="month" id="month" class="form-control" value="{{ $selectedMonth }}">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Top Sellers Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Auctioneer Name</th>
                <th>Total Sales</th>
                <th>Products Created</th>
                <th>Product Creation Month</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topSellers as $index => $seller)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $seller->auctioneer_name }}</td>
                    <td>PHP {{ number_format($seller->total_sales, 2) }}</td>
                    <td>{{ $seller->total_products }}</td>
                    <td>{{ \Carbon\Carbon::parse($seller->creation_month . '-01')->format('F Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
