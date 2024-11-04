<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageUserController extends Controller
{
    public function auctioneerIndex(){
        $users = User::where('role', 'auctioneer')->get();

        return view('admin.auctioneerIndex', compact('users'));
    }

    public function bidderIndex(){
        $users = User::where('role', 'bidder')->get();

        return view('admin.bidderIndex', compact('users'));
    }
}
