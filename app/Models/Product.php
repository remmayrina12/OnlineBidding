<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_class',
        'quantity',
        'description',
        'starting_price',
        'product_image',
        'auction_time',
        'auctioneer_id'

    ];



    public function auctioneer()
    {
        return $this->belongsTo(User::class, 'auctioneer_id');
    }
    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function highestBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }
}
