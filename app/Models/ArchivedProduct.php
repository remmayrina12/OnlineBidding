<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedProduct extends Model
{
    use HasFactory;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'original_product_id',
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
        'archived_at',
    ];
}

