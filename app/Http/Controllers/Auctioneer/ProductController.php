<?php

namespace App\Http\Controllers\Auctioneer;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Jobs\NotifyAuctionEnd;
use App\Models\ArchivedProduct;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AuctionEndedNotification;
use App\Notifications\ProductRequestNotification;
use App\Notifications\TwilioProductRequestNotification;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('auctioneer_id', Auth::id())
                            ->orderBy('created_at', 'desc') // Sort in descending order
                            ->get();
        $highestBids = $this->getHighestBidsForProducts($products);
        $bidCounts = $this->getBidCounts($products);

        return view('auctioneer.index', compact('products', 'highestBids', 'bidCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('auctioneer.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create and save the new product
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->starting_price = $request->starting_price;

        // Image upload handling
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('images', 'public');
            $product->product_image = $imagePath;
        }

        $product->auctioneer_id = Auth::id();
        $product->save();

        // Notify admins
        $adminName = 'admin';
        $specificAdmin = User::where('role', 'admin')->where('name', $adminName)->first(); // Replace $specificAdminId with the actual ID or condition
        if ($specificAdmin) {
            $specificAdmin->notify(new ProductRequestNotification($product));
        }

        // Redirect with success session
        return redirect()->back()->with('alert', [
                                            'type' => 'success',
                                            'message' => 'The Product is successfully requested.',
                                        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $currentTime = Carbon::now(); // Current time
        $query = $request->input('query');

        // Get all products with their auctioneer and highest bid (if necessary)
        $products = Product::where('product_post_status', '=', 'active')
            ->with(['auctioneer', 'bids']) // Eager load auctioneer and bids relationships
            ->where(function($q) use ($query) {
                $q->where('product_name', 'LIKE', "%{$query}%")
                ->orWhere('category', 'LIKE', "%{$query}%");
            })
            ->orderBy('auction_status', 'desc') // "closed" will be considered last as string sorting places it after "active"
            ->orderBy('created_at', 'desc') // Secondary sorting for remaining products
            ->get();

        // Initialize arrays to store the highest bids, and check if the user has already bid on the product
        $highestBids = $this->getHighestBidsForProducts($products);
        $alreadyBidOn = $this->getAlreadyBidOn($products);
        $bidCounts = $this->getBidCounts($products);
        $allBids = $this->getBidsForProducts($products);

        // Pass the products, highestBids, alreadyBidOn, and bidCounts to the view
        return view('home', compact('products', 'highestBids', 'alreadyBidOn', 'bidCounts', 'allBids', 'query'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('auctioneer.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',  // Validate quantity
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        // Find the existing product by its ID
        $product = Product::findOrFail($id);

        // Update the product with new details
        $product->product_name = $request->product_name;
        $product->category = $request->category;
        $product->quantity = $request->quantity;  // Update the quantity
        $product->description = $request->description;
        $product->starting_price = $request->starting_price;


        // Handle the image upload if a new image is provided
        if ($request->hasFile('product_image')) {
            // Delete the old image if it exists
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            // Upload and store the new image
            $imagePath = $request->file('product_image')->store('images', 'public');
            $product->product_image = $imagePath;
        }

        // Save the updated product
        $product->save();

        // Redirect to a success page or back to the dashboard with a success message
        return redirect()->route('auctioneer.index')->with('alert', [
                                                                        'type' => 'success',
                                                                        'message' => 'Product updated successfully.',
                                                                    ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the product by its ID
        $product = Product::findOrFail($id);

        // Archive the product by copying its data to the archived_products table
        ArchivedProduct::create([
            'original_product_id' => $product->id,
            'product_name' => $product->product_name,
            'category' => $product->category,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'starting_price' => $product->starting_price,
            'product_image' => $product->product_image,
            'auction_time' => $product->auction_time,
            'auction_status' => $product->auction_status,
            'auctioneer_id' => $product->auctioneer_id,
            'product_post_status' => $product->product_post_status,
            'archived_at' => Carbon::now(),
        ]);

        // Permanently delete the product from the products table
        $product->forceDelete();

        // Redirect back to the auctioneer index page
        return redirect()->route('auctioneer.index')->with('alert', [
                                                                        'type' => 'success',
                                                                        'message' => 'The Product deleted successfully.',
                                                                    ]);
    }

    public function archived()
    {
        // Retrieve archived products for the authenticated auctioneer
        $archivedProducts = ArchivedProduct::where('auctioneer_id', Auth::id())->get();

        return view('auctioneer.archived', compact('archivedProducts'));
    }


    private function getHighestBidsForProducts($products)
    {
        $highestBids = [];

        foreach ($products as $product) {
            $highestBid = Bid::where('product_id', $product->id)
                ->with('bidder')
                ->orderBy('amount', 'desc')
                ->first();

            $highestBids[$product->id] = $highestBid;
        }

        return $highestBids;
    }

    private function getBidCounts($products)
    {
        $bidCounts = [];

        foreach ($products as $product) {
            // Count the total number of bids for this product
            $bidCounts[$product->id] = Bid::where('product_id', $product->id)->count();

        }

        return $bidCounts;
    }

    private function getBidsForProducts($products)
    {
        $bids = [];
        foreach ($products as $product) {
            // Fetch all bids for this product, sorted by bid amount in descending order
            $bids[$product->id] = $product->bids->sortByDesc('amount');
        }
        return $bids;
    }

    private function getAlreadyBidOn($products)
    {
        // Get the currently authenticated bidder
        $currentBidder = Auth::user();

        $alreadyBidOn = [];

        // Loop through each product in the collection
        foreach ($products as $product) {
            // Check if the current bidder has already placed a bid on this product
            $userBid = Bid::where('product_id', $product->id)
                        ->where('bidder_id', $currentBidder->id) // Check for the current user
                        ->first(); // Get their bid if it exists

            // If the user has already bid on this product, store the product in the alreadyBidOn array
            if ($userBid) {
                $alreadyBidOn[$product->id] = true;
            }
        }

        return $alreadyBidOn;
    }



    public function filterByCategory($category)
    {

        // Fetch products in the selected category with active status and their auctioneer
        $products = Product::where('category', $category)
                            ->where('product_post_status', '=', 'active')
                            ->with('auctioneer')
                            ->orderBy('created_at', 'desc') // Sort in descending order
                            ->get();

        // Initialize arrays to store the highest bids and the products where the user has already bid
        $highestBids = $this->getHighestBidsForProducts($products);
        $alreadyBidOn = $this->getAlreadyBidOn($products);
        $bidCounts = $this->getBidCounts($products);

        // Pass the products, highestBids array, alreadyBidOn array, and bidCounts to the view
        return view('home', compact('products', 'highestBids', 'alreadyBidOn', 'bidCounts', 'category'));
    }

    public function end(Product $product, Request $request)
    {
        // Update auction status to 'closed' for the specific product
        $product->update(['auction_status' => 'closed']);

        // Get the highest bid for the product
        $highestBid = $this->getHighestBidsForProducts([$product])[$product->id] ?? null;

        if ($highestBid) {
            // Notify the highest bidder
            $highestBid->bidder->notify(new AuctionEndedNotification($product, 'winner'));


        // Notify other bidders (exclude the highest bidder)
        $otherBidders = Bid::where('product_id', $product->id)
            ->where('bidder_id', '!=', $highestBid->bidder->id)
            ->with('bidder')
            ->get();

        foreach ($otherBidders as $bid) {
            $bid->bidder->notify(new AuctionEndedNotification($product, 'ended'));
        }

        // Optionally, notify the auctioneer (product owner)
        $auctioneer = User::find($product->auctioneer_id); // Assuming `user_id` is the owner of the product
        if ($auctioneer) {
            $auctioneer->notify(new AuctionEndedNotification($product, 'auctioneer'));
        }

        // Check if the request is programmatic
        if ($request->query('trigger') === 'programmatic') {
            // Do not flash the success session
            return redirect()->back();
        }

        // Return a success message or redirect back to the page
        return redirect()->back()->with('alert', [
                                            'type' => 'success',
                                            'message' => 'Auction has been ended successfully, and notifications have been sent.',
                                        ]);
        }
    }
}
