<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bidder\BidController;
use App\Http\Controllers\Auctioneer\ProductController;

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

// Auctioneer Routes
Route::middleware(['auth', 'verified', 'roleManager:auctioneer'])->group(function () {
    Route::get('/auctioneer/create', [ProductController::class, 'create'])->name('auctioneer.create');
    Route::post('/auctioneer/store', [ProductController::class, 'store'])->name('auctioneer.store');
    Route::put('/auctioneer/update/{id}', [ProductController::class, 'update'])->name('auctioneer.update');
    Route::get('/auctioneer/edit/{id}', [ProductController::class, 'edit'])->name('auctioneer.edit');
    Route::get('/auctioneer/index', [ProductController::class, 'index'])->name('auctioneer.index');
    Route::delete('/auctioneer/destroy/{id}', [ProductController::class, 'destroy'])->name('auctioneer.destroy');


});

// Bidder Routes
Route::middleware(['auth', 'verified', 'roleManager:bidder'])->group(function () {
    Route::post('/bidder/store', [BidController::class, 'store'])->name('bidder.store');


});
