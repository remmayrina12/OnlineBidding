<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportsForTopSellerController extends Controller
{
    public function getTopSellers(Request $request)
    {
        // Get the selected month from the request
        $selectedMonth = $request->input('month');

        // Query to calculate the total sales for each auctioneer and count the number of products created per month
        $topSellers = DB::table('products')
            ->join('bids', function ($join) {
                $join->on('products.id', '=', 'bids.product_id')
                    ->whereRaw('bids.highest_bid = (SELECT MAX(highest_bid) FROM bids WHERE product_id = products.id)');
            })
            ->join('users', 'products.auctioneer_id', '=', 'users.id') // Join with users (auctioneers)
            ->select(
                'users.name as auctioneer_name', // Auctioneer's name
                DB::raw('SUM(bids.highest_bid) as total_sales'), // Calculate total sales
                DB::raw('COUNT(DISTINCT products.id) as total_products'), // Count the number of products created
                DB::raw('DATE_FORMAT(products.created_at, "%Y-%m") as creation_month') // Format the product creation date (Year-Month)
            )
            ->where('products.product_post_status', 'active') // Only consider active products
            ->where('products.auction_status', '=', 'closed') // Ensure the auction is over
            ->when($selectedMonth, function ($query, $selectedMonth) {
                return $query->whereRaw('DATE_FORMAT(products.created_at, "%Y-%m") = ?', [$selectedMonth]);
            })
            ->groupBy('products.auctioneer_id', 'users.name', 'creation_month')
            ->orderByDesc('total_sales')
            ->get();

        return view('admin.reportForTopSeller', compact('topSellers', 'selectedMonth'));
    }
}
