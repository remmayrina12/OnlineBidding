<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\ProductNotifyToAllBidders;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\confirm;
use App\Notifications\ProductStatusNotification;

class ManageProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manages = Product::with('auctioneer')->where('product_post_status', 'pending')->get();

        return view('admin.manageProducts', compact('manages'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function acceptProduct(string $id)
    {
        $data = Product::find($id);
        $data->product_post_status = 'active';
        $data->auction_time = Carbon::now()->addHours(3);
        $data->auction_status = 'open';
        $data->save();

        // Notify the specific user (product owner) after the product is accepted
        $auctioneer = User::find($data->auctioneer_id); // Assuming `user_id` is the owner of the product
        if ($auctioneer) {
            $auctioneer->notify(new ProductStatusNotification($data, 'active'));
        }

        $bidders = User::where('role', 'bidder')->get();
        foreach($bidders as $bidder){
            $bidder->notify(new ProductNotifyToAllBidders($data));
        }

        return redirect()->back()->with('alert', [
                                            'type' => 'success',
                                            'message' => 'The Product is activated.',
                                        ]);
    }

    public function rejectProduct(string $id)
        {
        $data = Product::find($id);
        $data->product_post_status = 'reject';
        $data->auction_status = 'closed';
        $data->save();

        // Notify the user after the product is rejected
        $auctioneer = User::find($data->auctioneer_id); // Assuming `user_id` is the owner of the product
        if ($auctioneer) {
            $auctioneer->notify(new ProductStatusNotification($data, 'reject'));
        }


        return redirect()->back()->with('alert', [
                                            'type' => 'failed',
                                            'message' => 'The Product is rejected.',
                                            ]);
        }
}
