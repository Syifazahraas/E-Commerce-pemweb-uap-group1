<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Buyer;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        // Ensure user has a buyer record if they are a member
        if ($user->isMember() && !$user->buyer) {
            Buyer::create(['user_id' => $user->id]);
            $user->refresh();
        }
        
        return view('buyer.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update User Model
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update or Create Buyer Model
        $buyer = $user->buyer ?? Buyer::create(['user_id' => $user->id]);

        if ($request->has('phone_number')) {
            $buyer->phone_number = $request->phone_number;
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($buyer->profile_picture) {
                Storage::disk('public')->delete($buyer->profile_picture);
            }

            // Store new picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $buyer->profile_picture = $path;
        }

        $buyer->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
