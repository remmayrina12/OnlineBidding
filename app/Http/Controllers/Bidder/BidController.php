<?php

namespace App\Http\Controllers\Bidder;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OutbidNotification;

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Get the highest bid for this product
        $highestBid = Bid::where('product_id', $request->product_id)->max('amount');
        // Get the current highest bidder
        $previousHighestBid = Bid::where('product_id', $request->product_id)
            ->where('amount', $highestBid)
            ->first();

        // Create the new bid
        $bid = new Bid;
        $bid->product_id = $request->product_id;
        $bid->bidder_id = Auth::id();
        $bid->amount = $request->amount;

        $product = Product::find($request->product_id);
        // Check if the auction time has passed
        if (Carbon::now() > $product->auction_time) {
            return redirect()->back()->with('alert', [
                                                'type' => 'failed',
                                                'message' => 'Bidding has been closed for this product.',
                                            ]);
        }

        // Check if the bid amount is lower than the auctioneer's starting price
        if ($request->amount < $product->starting_price) {
            return redirect()->back()->with('alert', [
                                                'type' => 'failed',
                                                'message' => 'Your bid must be higher than the starting price of ₱' . number_format($product->starting_price, 2),
                                            ]);
        }

        // Check if the new bid is higher than the current highest bid
        if ($request->amount > $highestBid) {
            // If the new bid is valid, save it as the highest bid
            $bid->amount = $request->amount;
            $bid->highest_bid = $request->amount;
            $bid->save();

            // Notify the previous highest bidder that they have been outbid
            // Notify the previous highest bidder that they have been outbid
        if ($previousHighestBid && $previousHighestBid->bidder_id !== Auth::id()) {
            $previousHighestBid->bidder->notify(new OutbidNotification($product, $request->amount));
        }


            return redirect()->route('bidder.show')->with('alert', [
                                                                        'type' => 'success',
                                                                        'message' => 'Your bid was successfully placed!',
                                                                    ]);
        } else {
            // If the new bid is lower or equal to the highest bid, return an error response
            session()->flash('alert', [
                                'type' => 'failed',
                                'message' => 'Your bid must be higher than the current highest bid of ₱' . number_format($highestBid, 2),
                                ]);
                return redirect()->back()->with('alert', [
                                                    'type' => 'failed',
                                                    'message' =>'Your bid must be higher than the current highest bid of ₱' . number_format($highestBid, 2),
                                                    ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Get the search query from the request
        $query = $request->input('query');

        // Fetch bids that the current user has placed
        $bids = Bid::with('product')
            ->where('bidder_id', $user->id) // Only get the bids by this user
            ->whereHas('product', function ($q) use ($query) {
                if ($query) {
                    $q->where('product_name', 'LIKE', "%{$query}%")
                    ->orWhere('category', 'LIKE', "%{$query}%"); // Search products by name
                }
            })
            ->orderBy('created_at', 'desc') // Sort in descending order
            ->paginate(10); // Add pagination

        // Initialize an array to store the highest bids for each product
        $highestBids = [];
        $bidCounts = [];

        // Loop through each bid to find the highest bid for the associated product
        foreach ($bids as $bid) {
            // Count the total number of bids for this product
            $bidCounts[$bid->product->id] = Bid::where('product_id', $bid->product->id)->count();

            // Find the highest bid for each product
            $highestBid = Bid::where('product_id', $bid->product->id)
                ->with('bidder') // Include the bidder's details
                ->orderBy('amount', 'desc')
                ->first();

            // Store the highest bid for this product
            $highestBids[$bid->product->id] = $highestBid;
        }

        // Pass the filtered bids, highest bids, and bid counts to the view
        return view('bidder.show', compact('bids', 'highestBids', 'bidCounts', 'query'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bid = Bid::findOrFail($id);
        return view('bidder.edit', compact('bid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Get the highest bid for this product
        $highestBid = Bid::where('product_id', $request->product_id)->max('amount');

        // Get the current highest bidder
        $previousHighestBid = Bid::where('product_id', $request->product_id)
            ->where('amount', $highestBid)
            ->first();

        // Find the current bid for this user on the product (if exists)
        $bid = Bid::where('product_id', $request->product_id)
                    ->where('bidder_id', Auth::id())
                    ->first();

        // If no bid exists for this user, create a new bid
        if (!$bid) {
            $bid = new Bid();
            $bid->product_id = $request->product_id;
            $bid->bidder_id = Auth::id();
        }

        $product = Product::find($request->product_id);

        // Check if the auction time has passed
        if (Carbon::now() > $product->auction_time) {
            return redirect()->back()->with('alert', [
                                                'type' => 'failed',
                                                'message' => 'Bidding has been closed for this product.',
                                                ]);
    }

        // Check if the bid amount is lower than the auctioneer's starting price
        if ($request->amount < $product->starting_price) {
            return redirect()->back()->with('alert', [
                                                'type' => 'failed',
                                                'message' => 'Your bid must be higher than the starting price of ₱' . number_format($product->starting_price, 2),
                                            ]);
        }

        // Check if the new bid is higher than the current highest bid
        if ($request->amount > $highestBid) {
            // If valid, update the bid amount
            $bid->amount = $request->amount;
            $bid->save();

        // Notify the previous highest bidder that they have been outbid
        if ($previousHighestBid && $previousHighestBid->bidder_id !== Auth::id()) {
            $previousHighestBid->bidder->notify(new OutbidNotification($product, $request->amount));
        }

            return redirect()->back()->with('alert', [
                                                'type' => 'success',
                                                'message' =>'Your bid was successfully placed!',
                                                ]);
        } else {
            // If the new bid is lower or equal to the highest bid, return an error response
            return redirect()->back()->with('alert', [
                                                'type' => 'failed',
                                                'message' => 'Your bid must be higher than the current highest bid of ₱' . number_format($highestBid, 2),
                                            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function filterByCategory($category)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Fetch products in the selected category with active status
        $products = Product::where('category', $category)
                            ->where('product_post_status', '=', 'active')
                            ->with('auctioneer')
                            ->get();

        // Fetch the bids the current user has placed on these products
        $bids = Bid::whereIn('product_id', $products->pluck('id')) // Only get bids for these products
                    ->where('bidder_id', $user->id) // Only get bids by this user
                    ->orderBy('created_at', 'desc') // Sort in descending order
                    ->with('product') // Include product and auctioneer details
                    ->get();

        // Initialize arrays to store the highest bids and bid counts for each product
        $highestBids = [];
        $bidCounts = [];

        // Loop through each product to find the highest bid and bid count
        foreach ($bids as $bid) {
            // Count the total number of bids for this product
            $bidCounts[$bid->product->id] = Bid::where('product_id', $bid->product->id)->count();

            // Find the highest bid for this product
            $highestBid = Bid::where('product_id', $bid->product->id)
                            ->with('bidder') // Include the bidder's details
                            ->orderBy('amount', 'desc')
                            ->first();

            // Store the highest bid for this product
            $highestBids[$bid->product->id] = $highestBid;
        }

        // Pass the filtered products, bids, highestBids, and bidCounts to the view
        return view('bidder.show', compact('products', 'bids', 'highestBids', 'bidCounts', 'category'));
    }

    public function showAuctionWin()
    {
        $user = Auth::user();

        // Find winning bids where the authenticated user is the highest bidder
        $winningBids = Bid::with(['product', 'bidder'])
            ->where('bidder_id', $user->id)
            ->whereIn('amount', function ($query) {
                // Subquery to get the maximum bid amount for each product
                $query->selectRaw('MAX(amount)')
                    ->from('bids')
                    ->groupBy('product_id');
            })
            ->orderBy('created_at', 'desc') // Sort in descending order
            ->get();

        // Initialize arrays to store the highest bids and bid counts for each product
        $highestBids = [];
        $bidCounts = [];
        $allBids = [];

        // Loop through each product to find the highest bid and bid count
        foreach ($winningBids as $bid) {

            // Get all bids for this product
            $allBids[$bid->product->id] = Bid::where('product_id', $bid->product->id)
            ->with('bidder')
            ->orderBy('amount', 'desc')
            ->get();

            // Count the total number of bids for this product
            $bidCounts[$bid->product->id] = Bid::where('product_id', $bid->product->id)->count();

            // Find the highest bid for this product
            $highestBid = Bid::where('product_id', $bid->product->id)
                            ->with('bidder') // Include the bidder's details
                            ->orderByDesc('amount') // Get the highest amount first
                            ->first();

            // Store the highest bid for this product if it matches the user's bid
            if ($highestBid && $highestBid->bidder_id == $user->id) {
                $highestBids[$bid->product->id] = $highestBid;
            }
        }

        // Pass only the authenticated user's winning bids to the view
        return view('bidder.showAuctionWin', compact('winningBids', 'highestBids', 'bidCounts', 'allBids'));
    }
}
