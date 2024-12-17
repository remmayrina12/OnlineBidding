<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bidder\BidController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\Auctioneer\ProductController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\ManageProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\ReportsForTopBidderController;
use App\Http\Controllers\Admin\ReportsForTopSellerController;
use App\Http\Controllers\Admin\ReportsForListOfWinningBidController;
use App\Http\Controllers\MarkLocationController;
use App\Http\Controllers\SMSController;
use App\Models\MarkLocation;

Route::get('/', function () {
    return view('LandingPage');
});

Route::middleware(['auth', 'checkStatus'])->group(function () {

    Route::get('/bidder/dashboard', function () {
        return view('bidder.dashboard');
    })->middleware(['auth', 'roleManager:bidder'])->name('bidder');

    Route::get('/auctioneer/dashboard', function () {
        return view('auctioneer.dashboard');
    })->middleware(['auth', 'roleManager:auctioneer'])->name('auctioneer');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth', 'roleManager:admin'])->name('admin');

    Route::get('/home', [ProductController::class, 'show'])->name('home.show');

    Route::get('/home/{category}', [ProductController::class, 'filterByCategory'])->name('home.category');

    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/mark-as-read/{id}', [UserNotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

        Route::get('/send-sms', [SMSController::class, 'send']);
    });

    // Auctioneer Routes
    Route::middleware(['auth', 'verified', 'roleManager:auctioneer'])->group(function () {
        Route::get('/auctioneer/create', [ProductController::class, 'create'])->name('auctioneer.create');
        Route::post('/auctioneer/store', [ProductController::class, 'store'])->name('auctioneer.store');
        Route::put('/auctioneer/update/{id}', [ProductController::class, 'update'])->name('auctioneer.update');
        Route::get('/auctioneer/edit/{id}', [ProductController::class, 'edit'])->name('auctioneer.edit');
        Route::get('/auctioneer/index', [ProductController::class, 'index'])->name('auctioneer.index');
        Route::delete('/auctioneer/destroy/{id}', [ProductController::class, 'destroy'])->name('auctioneer.destroy');
        Route::get('/auctioneer/archived', [ProductController::class, 'archived'])->name('auctioneer.archived');

        Route::post('/auctioneer/end/{product}', [ProductController::class, 'end'])->name('auctioneer.end');
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
        Route::get('/admin/reportIndex', [ReportController::class, 'index'])->name('reportIndex.index');
        Route::get('/admin/reportIndex/status/{id}', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');

        Route::get('/admin/reportForListOfWinningBid', [ReportsForListOfWinningBidController::class, 'getTopRanks'])->name('reportForListOfWinningBid.getTopRanks');
        Route::get('/admin/reportForTopBidder', [ReportsForTopBidderController::class, 'getTopBidders'])->name('reportForTopBidder.getTopBidders');
        Route::get('/admin/reportForTopSeller', [ReportsForTopSellerController::class, 'getTopSellers'])->name('reportForTopSeller.getTopSellers');


        Route::post('/admin/users/{id}/suspend', [ManageUserController::class, 'suspendUser'])->name('users.suspend');
        Route::post('/admin/users/{id}/ban', [ManageUserController::class, 'banUser'])->name('users.ban');
        Route::post('/admin/users/{id}/unsuspend', [ManageUserController::class, 'unsuspendUser'])->name('users.unsuspend');
        Route::post('/admin/users/{id}/unban', [ManageUserController::class, 'unbanUser'])->name('users.unban');

    });

    //Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update/{userId}', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
        Route::get('/profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/chat/index/{receiverId}', [MessageController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [MessageController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages/fetchMessages/{receiverId}', [MessageController::class, 'fetchMessages'])->name('chat.messages.fetchMessages');
    });

    //Rating Routes
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

    //Report Routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::middleware(['auth'])->group(function () {
        Route::get('/markLocation/index/{userId}', [MarkLocationController::class, 'index'])->name('markLocation.index');
        Route::get('/markLocation/create/{userId}', [MarkLocationController::class, 'create'])->name('markLocation.create');
        Route::post('/markLocation/store', [MarkLocationController::class, 'store'])->name('markLocation.store');
        Route::delete('/markLocation/destroy/{id}', [MarkLocationController::class, 'destroy'])->name('markLocation.destroy');
    });
});

Auth::routes(['verify' => true]);
