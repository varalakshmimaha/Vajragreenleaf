<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    /**
     * Get referral tree for the authenticated user
     */
    public function getReferralTree(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $maxLevel = $request->query('maxLevel', 3);
        
        $referrals = $user->getAllReferrals($maxLevel);
        $hasMore = $user->hasReferralsBeyondLevel($maxLevel);

        return response()->json([
            'referralID' => $user->referral_id,
            'name' => $user->name,
            'referrals' => $referrals,
            'hasMore' => $hasMore,
            'totalDirectReferrals' => $user->referrals()->count(),
        ]);
    }

    /**
     * Get referral tree for a specific user by referral ID
     */
    public function getReferralTreeByReferralId(Request $request, $referralId)
    {
        $user = User::where('referral_id', $referralId)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $maxLevel = $request->query('maxLevel', 3);
        
        $referrals = $user->getAllReferrals($maxLevel);
        $hasMore = $user->hasReferralsBeyondLevel($maxLevel);

        return response()->json([
            'referralID' => $user->referral_id,
            'name' => $user->name,
            'referrals' => $referrals,
            'hasMore' => $hasMore,
            'totalDirectReferrals' => $user->referrals()->count(),
        ]);
    }

    /**
     * Get referral statistics
     */
    public function getReferralStats()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Count referrals by level
        $level1Count = $user->referrals()->count();
        $level2Count = 0;
        $level3Count = 0;
        
        foreach ($user->referrals as $level1) {
            $level2Count += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $level3Count += $level2->referrals()->count();
            }
        }

        $totalReferrals = $level1Count + $level2Count + $level3Count;

        return response()->json([
            'referralID' => $user->referral_id,
            'totalReferrals' => $totalReferrals,
            'level1' => $level1Count,
            'level2' => $level2Count,
            'level3' => $level3Count,
        ]);
    }

    /**
     * Display the referral dashboard page
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login');
        }

        return view('referrals.dashboard', compact('user'));
    }
}
