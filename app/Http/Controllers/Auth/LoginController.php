<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function store(Request $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $authUserRole = Auth::User()->role;

        if($authUserRole == 'admin'){
            return redirect()->intended(route('admin', absolute: false));
        }elseif($authUserRole == 'auctioneer'){
            return redirect()->intended(route('auctioneer', absolute: false));
        }else{
            return redirect()->intended(route('bidder', absolute: false));
        }
    }
}