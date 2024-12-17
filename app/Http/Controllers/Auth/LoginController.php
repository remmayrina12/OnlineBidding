<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
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

    public function login(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Get the authenticated user
            $user = Auth::user();

            // Check if the user is banned
            if ($user->status === 'banned') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is banned. Contact support for assistance.');
            }

            // Check if the user is suspended
            if ($user->status === 'suspended') {
                // Check if the suspension period has ended
                if ($user->suspension_until && Carbon::now('Asia/Manila')->greaterThanOrEqualTo($user->suspension_until)) {
                    // Update the user's status to active
                    $user->update([
                        'status' => 'active',
                        'suspension_until' => null,
                    ]);
                } else {
                    // Calculate the remaining suspension period
                    $suspensionEnd = Carbon::parse($user->suspension_until)->format('F j, Y, g:i a'); // Format date and time
                    Auth::logout();
                    return redirect()->route('login')->with(
                        'error',
                        "Your account is suspended. You can log in on {$suspensionEnd}."
                    );
                }
            }

            // If account is active, proceed to the intended page
            return redirect()->intended('home');
        }

        // If credentials are invalid, redirect back with an error
        return redirect()->route('login')->with('error', 'Invalid credentials.');
    }

}
