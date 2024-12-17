<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarkLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkLocationController extends Controller
{
    public function index($userId)
    {
        // Fetch the user from the database to ensure they exist
        $user = User::findOrFail($userId);

        // Fetch the locations for this specific user
        $markLocation = MarkLocation::where('user_id', $user->id)->get(); // Fetch locations owned by this user

        return view('MarkLocation.index', compact('markLocation', 'user'));
    }

    public function create($userId)
    {
        // Fetch the user to ensure they exist
        $user = User::findOrFail($userId);

        // Fetch the locations associated with this user
        $markLocations = MarkLocation::where('user_id', $user->id)->get();

        return view('markLocation.create', compact('markLocations', 'user'));
    }
    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Create a MarkLocation record with the currently authenticated user's ID
        MarkLocation::create([
            'name' => $validatedData['name'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'user_id' => Auth::id(), // Ensure the user is authenticated
        ]);

        return redirect()->back()->with('success', 'Location saved successfully!');
    }

    public function destroy($id)
    {
        $location = MarkLocation::find($id);
        if ($location) {
            $location->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
