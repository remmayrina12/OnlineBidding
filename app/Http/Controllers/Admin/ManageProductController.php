<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ProductStatusNotification;

use function Laravel\Prompts\confirm;

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
        $data->auction_time = now()->addHour();
        $data->save();

        return redirect()->back()->with('success', 'Product post status is change to active.');
    }

    public function rejectProduct(string $id)
    {
        $data = Product::find($id);
        $data->product_post_status = 'reject';
        $data->closed_at = now();
        $data->save();

        return redirect()->back()->with('failed', 'Product post status is change to rejected.');
    }
}
