<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->Integer('quantity'); // Add quantity field
            $table->text('description');
            $table->decimal('starting_price', 10, 2);
            $table->string('product_image')->nullable(); // Add image field to store image path, can be nullable
            $table->timestamp('auction_time')->nullable(); // Store auction end time
            $table->foreignId('auctioneer_id')->constrained('users')->onDelete('cascade'); // Reference to auctioneer
            $table->string('product_post_status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
