<?php

namespace App\Http\Controllers\Auctioneer;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // Validate the incoming request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',  // Validate quantity
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image
        ]);

        // Create a new product
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->category = $request->category;
        $product->quantity = $request->quantity;  // Store the quantity
        $product->description = $request->description;
        $product->starting_price = $request->starting_price;

        // Handle the image upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('images', 'public');
            $product->product_image = $imagePath;  // Store the path to the uploaded image
        }

        $product->auctioneer_id = Auth::id();  // Store the ID of the logged-in auctioneer

        // Save the product
        $product->save();

        // Redirect to a success page or back to the dashboard with a success message
        return redirect()->route('auctioneer.index')->with('success', 'Product requested successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Get all products with their auctioneer
        $products = Product::where('product_post_status', '=', 'active')
                            ->with('auctioneer')
                            ->orderBy('created_at', 'desc') // Sort in descending order
                            ->get();

        // Initialize arrays to store the highest bids and the products where the user has already bid
        $highestBids = $this->getHighestBidsForProducts($products);
        $alreadyBidOn = $this->getAlreadyBidOn($products);
        $bidCounts = $this->getBidCounts($products);

        // Pass the products, highestBids array, and alreadyBidOn array to the view
        return view('home', compact('products', 'highestBids', 'alreadyBidOn', 'bidCounts'));
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
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image
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
        return redirect()->route('auctioneer.index')->with('success', 'Product updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the product by its ID
        $product = Product::findOrFail($id);

        // Soft delete the product
        $product->delete();

        // Redirect back to the auctioneer index page
        return redirect()->route('auctioneer.index')->with('success', 'Product archived successfully.');
    }

    public function archived()
    {
        // Retrieve only soft-deleted (archived) products for the authenticated auctioneer
        $archivedProducts = Product::onlyTrashed()->where('auctioneer_id', Auth::id())->get();

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


}
