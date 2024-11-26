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

    public function login(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Check if the user is banned or suspended
            $user = Auth::user();
            if ($user->status === 'banned') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is banned. Contact support for assistance.');
            }

            if ($user->status === 'suspended') {
                // Calculate the remaining suspension period
                $remainingDays = now()->diffInDays($user->suspension_until);
                $suspensionEnd = \Carbon\Carbon::parse($user->suspension_until)->format('F j, Y, g:i a'); // Format date and time

                if ($remainingDays < 1) {
                    $remainingDays = 1;
                }

                Auth::logout();
                return redirect()->route('login')->with(
                    'error',
                    "Your account is suspended. You can log in after {$remainingDays} day(s), on {$suspensionEnd}."
                );
            }

            // If account is active, proceed to the intended page
            return redirect()->intended('home');
        }

        // If credentials are invalid, redirect back with an error
        return redirect()->route('login')->with('error', 'Invalid credentials.');
    }
}
