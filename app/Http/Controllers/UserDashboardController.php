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
        ]);

        $user->update([
            'name' => $request->name,
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

    public function updateSponsor(Request $request)
    {
        $user = auth()->user();

        // Check if user already has a sponsor
        if ($user->sponsor_referral_id || $user->sponsor_id) {
            return back()->withErrors(['sponsor_id' => 'You already have a sponsor assigned.']);
        }

        $request->validate([
            'sponsor_id' => 'required|string',
        ]);

        $sponsorValue = $request->sponsor_id;
        
        // Find sponsor by referral_id or username
        $sponsor = \App\Models\User::where('referral_id', $sponsorValue)
            ->orWhere('username', $sponsorValue)
            ->first();

        if (!$sponsor) {
            return back()->withErrors(['sponsor_id' => 'Invalid Sponsor ID.']);
        }

        // Prevent self-sponsoring
        if ($sponsor->id === $user->id) {
            return back()->withErrors(['sponsor_id' => 'You cannot sponsor yourself.']);
        }

        // Prevent circular sponsorship (basic check - immediate parent)
        if ($sponsor->sponsor_referral_id === $user->referral_id) {
             return back()->withErrors(['sponsor_id' => 'Circular sponsorship is not allowed.']);
        }

        // Update user with sponsor details
        $user->update([
            'sponsor_referral_id' => $sponsor->referral_id,
            'sponsor_id' => $sponsor->username, // Legacy support
        ]);

        return back()->with('success', 'Sponsor added successfully!');
    }
}
