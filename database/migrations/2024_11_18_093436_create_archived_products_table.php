<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedProductsTable extends Migration
{
    public function up()
    {
        Schema::create('archived_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_product_id')->index(); // Tracks the original product
            $table->string('product_name');
            $table->string('category');
            $table->integer('quantity');
            $table->text('description');
            $table->decimal('starting_price', 10, 2);
            $table->string('product_image')->nullable();
            $table->timestamp('auction_time')->nullable();
            $table->string('auction_status')->default('open');
            $table->unsignedBigInteger('auctioneer_id'); // References the auctioneer
            $table->string('product_post_status')->default('pending');
            $table->timestamp('archived_at'); // Date when archived
            $table->timestamps();

            // Add foreign key constraint for auctioneer_id
            $table->foreign('auctioneer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('archived_products');
    }
}
