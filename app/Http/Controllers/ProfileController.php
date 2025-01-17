<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {

        // Get the authenticated user with their profile info
        $user = Auth::user()->load('info'); // Ensure 'info' is defined in the User model

        // Pass the user data to the edit profile view
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request, $userId)
    {
        // Ensure only authenticated user updates their own profile
        if ($userId != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:users,email,' . $userId,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact_number'  => 'nullable|string|max:15',
            'address'         => 'nullable|string',
            'valid_id'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Retrieve the user and user info
        $user = User::findOrFail($userId);
        $userinfo = UserInfo::where('user_id', $userId)->firstOrCreate(['user_id' => $userId]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($userinfo->profile_picture) {
                Storage::delete($userinfo->profile_picture);
            }
            // Store the new profile picture
            $userinfo->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Handle valid ID upload
        if ($request->hasFile('valid_id')) {
            // Delete old valid ID if it exists
            if ($userinfo->valid_id) {
                Storage::delete($userinfo->valid_id);
            }
            // Store the new valid ID
            $userinfo->valid_id = $request->file('valid_id')->store('valid_ids', 'public');
        }

        // Update user and user info fields
        $user->name = $request->name;
        $user->email = $request->email;
        $userinfo->contact_number = $request->contact_number;
        $userinfo->address = $request->address;

        // Save user and user info
        if ($user->save() && $userinfo->save()) {
            return redirect()->back()->with('alert', [
                                                'type' => 'success',
                                                'message' => 'Profile updated successfully.',
                                            ]);
        } else {
            return redirect()->back()->with('alert', [
                                                'type' => 'error',
                                                'message' =>'Failed to update profile.',
                                            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('alert', [
                                                                    'type' => 'success',
                                                                    'message' => 'Password updated successfully.',
                                                                ]);
    }

    public function show($id, Request $request)
    {
        $query = $request->input('query');

        // If a search query is provided
        if ($query) {
            $user = User::where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->where('role', '!=', 'admin' ) // Exclude admin users
                ->where('role', '!=', 'admin2' )
                ->first();

            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'User not found or not accessible.']);
            }

            return view('profile.show', compact('user', 'query'));
        }

        // If no query is provided, check access for the requested user ID
        if (Auth::check() && Auth::user()->id == $id) {
            // Show the logged-in user's profile
            $user = Auth::user();
        } else {
            // Fetch the requested user's profile by ID, excluding admins
            $user = User::where('id', $id)
                        ->where('role', '!=', 'admin')
                        ->first();

            if (!$user) {
                abort(404, 'User not found or not accessible.');
            }
        }

        return view('profile.show', compact('user'));
    }
}
