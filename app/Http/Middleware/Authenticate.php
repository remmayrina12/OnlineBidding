<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
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
                    // Log to verify if the code reaches here
                    Log::info("Suspension period over for user ID: {$user->id}");

                    // If suspension period is over, reactivate the account
                    $updated = $user->update(['status' => 'active', 'suspension_until' => null]);

                    // Log the result of the update operation
                    Log::info("User suspension status updated: " . ($updated ? 'Success' : 'Failed'));
                } else {
                    // If the suspension is still active, log the user out
                    $remainingDays = now()->diffInDays($user->suspension_until, false); // Negative if remaining days are in the past
                    auth::logout();
                    return redirect()->route('login')->with('error', "Your account is suspended. You can log in after {$remainingDays} day(s).");
                }
            }
        }

        return $next($request);
    }

}

