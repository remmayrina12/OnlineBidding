<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Models\Bid;
use App\Notifications\AuctionEndedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAuctionEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = $this->product;

        // Get the highest bid for the product
        $highestBid = Bid::where('product_id', $product->id)
            ->orderBy('bid_amount', 'desc')
            ->first();

        if ($highestBid) {
            // Notify the highest bidder
            $highestBid->bidder->notify(new AuctionEndedNotification($product, 'winner'));

            // Notify other bidders (exclude the highest bidder)
            $otherBidders = User::whereIn('id', function ($query) use ($product, $highestBid) {
                $query->select('bidder_id')
                    ->from('bids')
                    ->where('product_id', $product->id)
                    ->where('bidder_id', '!=', $highestBid->bidder->id);
            })->get();

            foreach ($otherBidders as $bidder) {
                $bidder->notify(new AuctionEndedNotification($product, 'ended'));
            }
        } else {
            // Optional: Handle case where no bids were placed
            // Notify the auctioneer that the product received no bids
        }

        // Notify the auctioneer (product owner)
        if ($product->auctioneer) {
            $product->auctioneer->notify(new AuctionEndedNotification($product, 'auctioneer'));
        }
    }
}
