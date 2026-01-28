<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $fileUpload;

    public function __construct(FileUploadService $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    /**
     * Display a listing of website customers (users with role='user')
     */
    public function index(Request $request)
    {
        set_time_limit(120);

        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('referral_id', 'like', '%' . $request->search . '%')
                  ->orWhere('sponsor_referral_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query
            ->with(['sponsorByReferralId:id,name,referral_id'])
            ->select('id', 'name', 'mobile', 'username', 'referral_id', 'sponsor_id', 'sponsor_referral_id', 'role', 'is_active', 'last_login_at', 'created_at', 'avatar')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->query('ajax') || $request->wantsJson()) {
            $data = $customers->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'mobile' => $u->mobile,
                    'username' => $u->username,
                    'referral_id' => $u->referral_id,
                    'sponsor_referral_id' => $u->sponsor_referral_id,
                    'sponsor_name' => $u->sponsorByReferralId?->name ?? null,
                    'is_active' => (bool) $u->is_active,
                    'last_login_at' => $u->last_login_at ? $u->last_login_at->toDateTimeString() : null,
                ];
            });

            return response()->json([
                'data' => $data,
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
            ]);
        }

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Ensure we're viewing a customer (role='user')
        if ($customer->role !== 'user') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Invalid customer.');
        }

        $customer->load('sponsorByReferralId', 'referrals');

        // Get referral statistics
        $stats = [
            'level1' => $customer->referrals()->count(),
            'level2' => 0,
            'level3' => 0,
        ];

        foreach ($customer->referrals as $level1) {
            $stats['level2'] += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $stats['level3'] += $level2->referrals()->count();
            }
        }

        $stats['total'] = $stats['level1'] + $stats['level2'] + $stats['level3'];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show form for editing the specified customer
     */
    public function edit(User $customer)
    {
        if ($customer->role !== 'user') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Invalid customer.');
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'user') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Invalid customer.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $customer->id,
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus(User $customer)
    {
        if ($customer->role !== 'user') {
            return response()->json(['error' => 'Invalid customer.'], 403);
        }

        $customer->update(['is_active' => !$customer->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $customer->is_active,
            'message' => $customer->is_active ? 'Customer activated.' : 'Customer deactivated.',
        ]);
    }

    /**
     * Remove the specified customer
     */
    public function destroy(User $customer)
    {
        if ($customer->role !== 'user') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Invalid customer.');
        }

        if ($customer->avatar) {
            $this->fileUpload->delete($customer->avatar);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Get referral tree for a specific customer
     */
    public function getReferralTree(Request $request, User $customer)
    {
        $maxLevel = $request->query('maxLevel', 3);

        $referrals = $customer->getAllReferrals($maxLevel);
        $hasMore = $customer->hasReferralsBeyondLevel($maxLevel);

        return response()->json([
            'referralID' => $customer->referral_id,
            'name' => $customer->name,
            'referrals' => $referrals,
            'hasMore' => $hasMore,
            'totalDirectReferrals' => $customer->referrals()->count(),
        ]);
    }
}
