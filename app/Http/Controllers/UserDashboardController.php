<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Calculate referral statistics
        $stats = [
            'level1' => $user->referrals()->count(),
            'level2' => 0,
            'level3' => 0,
        ];
        
        foreach ($user->referrals as $level1) {
            $stats['level2'] += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $stats['level3'] += $level2->referrals()->count();
            }
        }
        
        $stats['total'] = $stats['level1'] + $stats['level2'] + $stats['level3'];
        
        // Generate referral link
        $referralLink = url('/register?ref=' . $user->referral_id);
        
        return view('frontend.dashboard.index', compact('user', 'stats', 'referralLink'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
