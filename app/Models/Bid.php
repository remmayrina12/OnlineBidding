<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'bidder_id',
        'amount',
        'highest_bid'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user (bidder) who placed this bid.
     */
    public function bidder()
    {
        return $this->belongsTo(User::class);
    }
}
