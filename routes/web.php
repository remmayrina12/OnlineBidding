<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bidder\BidController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auctioneer\ProductController;
use App\Http\Controllers\Admin\ManageProductController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('home', [LoginController::class, 'store']);

Route::get('/bidder/dashboard', function () {
    return view('bidder.dashboard');
})->middleware(['auth', 'roleManager:bidder'])->name('bidder');

Route::get('/auctioneer/dashboard', function () {
    return view('auctioneer.dashboard');
})->middleware(['auth', 'roleManager:auctioneer'])->name('auctioneer');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'roleManager:admin'])->name('admin');

Auth::routes();

Route::get('/home', [ProductController::class, 'show'])->name('home.show');

Route::get('/home/{category}', [ProductController::class, 'filterByCategory'])->name('home.category');

// Auctioneer Routes
Route::middleware(['auth', 'verified', 'roleManager:auctioneer'])->group(function () {
    Route::get('/auctioneer/create', [ProductController::class, 'create'])->name('auctioneer.create');
    Route::post('/auctioneer/store', [ProductController::class, 'store'])->name('auctioneer.store');
    Route::put('/auctioneer/update/{id}', [ProductController::class, 'update'])->name('auctioneer.update');
    Route::get('/auctioneer/edit/{id}', [ProductController::class, 'edit'])->name('auctioneer.edit');
    Route::get('/auctioneer/index', [ProductController::class, 'index'])->name('auctioneer.index');
    Route::delete('/auctioneer/destroy/{id}', [ProductController::class, 'destroy'])->name('auctioneer.destroy');
    Route::get('/auctioneer/archived', [ProductController::class, 'archived'])->name('auctioneer.archived');
});

// Bidder Routes
Route::middleware(['auth', 'verified', 'roleManager:bidder'])->group(function () {
    Route::post('/bidder/store', [BidController::class, 'store'])->name('bidder.store');
    Route::get('/bidder/show', [BidController::class, 'show'])->name('bidder.show');
    Route::put('/bidder/update/{id}', [BidController::class, 'update'])->name('bidder.update');
    Route::get('/bidder/edit/{id}', [BidController::class, 'edit'])->name('bidder.edit');
    Route::get('/bidder/showAuctionWin', [BidController::class, 'showAuctionWin'])->name('bidder.showAuctionWin');
    Route::get('/bidder/{category}', [BidController::class, 'filterByCategory'])->name('bidder.category');

});

// Admin Routes
Route::middleware(['auth', 'verified', 'roleManager:admin'])->group(function () {
    Route::get('/admin/manageProduct', [ManageProductController::class, 'index'])->name('admin.manageProduct');
    Route::get('/admin/acceptProduct/{id}', [ManageProductController::class, 'acceptProduct'])->name('admin.acceptProduct');
    Route::get('/admin/rejectProduct/{id}', [ManageProductController::class, 'rejectProduct'])->name('admin.rejectProduct');
    Route::get('/admin/auctioneerIndex', [ManageUserController::class, 'auctioneerIndex'])->name('admin.auctioneerIndex');
    Route::get('/admin/bidderIndex', [ManageUserController::class, 'bidderIndex'])->name('admin.bidderIndex');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
