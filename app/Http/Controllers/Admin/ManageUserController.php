<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageUserController extends Controller
{
    public function auctioneerIndex(Request $request)
{
    $query = $request->input('query');

    $users = User::where('role', 'auctioneer') // Ensure the role condition is always applied
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                })->get();

    return view('admin.auctioneerIndex', compact('users'));
}

public function bidderIndex(Request $request)
{
    $query = $request->input('query');

    $users = User::where('role', 'bidder') // Ensure the role condition is always applied
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                })->get();

    return view('admin.bidderIndex', compact('users'));
}


    public function suspendUser(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|integer|min:1', // Validate as an integer
        ]);

        $user = User::findOrFail($id);

        // Cast `days` to an integer
        $suspensionDays = (int) $request->input('days');

        $user->update([
            'status' => 'suspended',
            'suspension_until' => Carbon::now('Asia/Manila')->addDays($suspensionDays), // Add days correctly

        ]);

        return redirect()->back()->with('alert', [
                                            'type' => 'success',
                                            'message' => "User has been suspended for {$suspensionDays} day(s).",
                                            ]);
    }


    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'banned',
            'suspension_until' => null, // No suspension date needed for banned users
        ]);

        return redirect()->back()->with('alert', [
                                            'type' => 'success',
                                            'message' => 'User has been banned.',
                                            ]);
    }

    // Unsuspend a user
    public function unsuspendUser($id)
    {
        $user = User::findOrFail($id);

        // Check if the user is suspended
        if ($user->status === 'suspended') {
            $user->update([
                'status' => 'active',
                'suspension_until' => null, // Clear the suspension end date
            ]);

            return redirect()->back()->with('alert', [
                                                'type' => 'success',
                                                'message' => 'User has been unsuspended.',
                                            ]);
        }

        return redirect()->back()->with('alert', [
                                            'type' => 'error',
                                            'message' => 'User is not suspended.',
                                        ]);
    }

    // Unban a user
    public function unbanUser($id)
    {
        $user = User::findOrFail($id);

        // Check if the user is banned
        if ($user->status === 'banned') {
            $user->update(['status' => 'active']);

            return redirect()->back()->with('alert', [
                                                'type' => 'success',
                                                'message' => 'User has been unbanned.',
                                            ]);
        }

        return redirect()->back()->with('alert', [
                                            'type' => 'error',
                                            'message' => 'User is not banned.',
                                        ]);
    }
}
