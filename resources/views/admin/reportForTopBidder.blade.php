@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Top Bidders Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reportForTopBidder.getTopBidders') }}" class="mb-4">
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

    <!-- Top Bidders Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Bidder Name</th>
                <th>Total Winning Amount</th>
                <th>Auctions Won</th>
                <th>Month</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topBidders as $index => $bidder)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bidder->bidder_name }}</td>
                    <td>₱{{ number_format($bidder->total_amount, 2) }}</td>
                    <td>{{ $bidder->total_wins }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $bidder->month)->format('F Y') }}</td>
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
