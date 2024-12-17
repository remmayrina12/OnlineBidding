<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportsForListOfWinningBidController extends Controller
{
    public function getTopRanks(Request $request)
    {
        // Get the selected month from the request or default to null
        $selectedMonth = $request->input('month', null);

        // Query to fetch products with their highest bid details
        $highestBidProducts = DB::table('products')
            ->join('bids', function ($join) {
                $join->on('products.id', '=', 'bids.product_id')
                    ->whereRaw('bids.amount = (SELECT MAX(amount) FROM bids WHERE product_id = products.id)');
            })
            ->join('users as bidders', 'bids.bidder_id', '=', 'bidders.id') // Join with users as bidders
            ->join('users as auctioneers', 'products.auctioneer_id', '=', 'auctioneers.id') // Join with users as auctioneers
            ->select(
                'products.id as product_id',
                'products.product_name as product_name',
                'bidders.name as bidder_name', // Bidder's name
                'auctioneers.name as auctioneer_name', // Auctioneer's name
                'bids.amount as highest_bid', // Highest bid amount
                'products.created_at as created_at', // Product creation time
                'products.auction_time as auction_time' // Auction end time
            )
            ->where('products.auction_status', '=', 'closed') // Ensure the auction is closed
            ->when($selectedMonth, function ($query, $selectedMonth) {
                return $query->whereRaw('DATE_FORMAT(products.auction_time, "%Y-%m") = ?', [$selectedMonth]);
            })
            ->orderByDesc('bids.amount') // Sort by highest bid
            ->get();

        return view('admin.reportForListOfWinningBid', compact('highestBidProducts', 'selectedMonth'));
    }
}
