@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Top Winning Bids Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reportForListOfWinningBid.getTopRanks') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="month">Filter by Month:</label>
                <input type="month" name="month" id="month" class="form-control" value="{{ $selectedMonth ?? '' }}">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Winning Bids Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Product Name</th>
                <th>Bidder Name</th>
                <th>Auctioneer Name</th>
                <th>Highest Bid</th>
                <th>Product Created At</th>
                <th>Auction Ended At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($highestBidProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->bidder_name }}</td>
                    <td>{{ $product->auctioneer_name }}</td>
                    <td>₱{{ number_format($product->highest_bid, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($product->auction_time)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
