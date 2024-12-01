<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'product_name',
        'category',
        'quantity',
        'description',
        'starting_price',
        'product_image',
        'auction_time',
        'auction_status',
        'auctioneer_id',
        'product_post_status',
    ];

    public function getEndTimeAttribute()
    {
        return $this->auction_time ? Carbon::parse($this->auction_time)->addHour() : null;
    }

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
