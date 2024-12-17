<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportsForTopBidderController extends Controller
{
    public function getTopBidders(Request $request)
    {
        // Get the selected month from the request or default to null
        $selectedMonth = $request->input('month', null);

        $topBidders = DB::table('bids')
            ->join('products', 'bids.product_id', '=', 'products.id') // Join with products table
            ->join('users', 'bids.bidder_id', '=', 'users.id') // Join with users table (bidders)
            ->where('products.auction_status', '=', 'closed') // Only consider ended auctions
            ->whereRaw('bids.amount = (SELECT MAX(amount) FROM bids WHERE product_id = products.id)') // Only winning bids
            ->select(
                'users.name as bidder_name', // Bidder's name
                DB::raw('SUM(bids.amount) as total_amount'), // Total amount of winning bids
                DB::raw('COUNT(bids.id) as total_wins'), // Count the number of auctions won
                DB::raw('DATE_FORMAT(products.created_at, "%Y-%m") as month') // Group by month
            )
            ->when($selectedMonth, function ($query, $selectedMonth) {
                return $query->whereRaw('DATE_FORMAT(products.created_at, "%Y-%m") = ?', [$selectedMonth]);
            })
            ->groupBy('bids.bidder_id', 'users.name', DB::raw('DATE_FORMAT(products.created_at, "%Y-%m")')) // Group by bidder and month
            ->orderByDesc('total_wins') // Order by number of auctions won
            ->get();

        return view('admin.reportForTopBidder', compact('topBidders', 'selectedMonth'));
    }

}
