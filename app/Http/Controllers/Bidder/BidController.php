<?php

namespace App\Http\Controllers\Bidder;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        // Create the new bid
        $bid = new Bid;
        $bid->product_id = $request->product_id;
        $bid->bidder_id = Auth::id();
        $bid->amount = $request->amount;


        $product = Product::find($request->product_id);
        // Check if the auction time has passed
        if (now() > $product->auction_time) {
            session()->flash('failed', 'Bidding has closed for this product.');
            return redirect()->back();
        }

        // Check if the bid amount is lower than the auctioneer's starting price
        if ($request->amount < $product->starting_price) {
            session()->flash('failed', 'Your bid must be higher than the starting price of ' . number_format($product->starting_price, 2));
            return redirect()->back();
        }

        // Check if the new bid is higher than the current highest bid
        if ($request->amount > $highestBid) {
            // If the new bid is valid, save it as the highest bid
            $bid->amount = $request->amount;
            $bid->highest_bid = $request->amount;
            $bid->save();

            session()->flash('success', 'Your bid was successfully placed!');
            return redirect()->back();
        } else {
            // If the new bid is lower or equal to the highest bid, return an error response
            session()->flash('failed', 'Your bid must be higher than the current highest bid of ' . number_format($highestBid, 2));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
