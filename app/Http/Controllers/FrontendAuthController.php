<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendAuthController extends Controller
{
    public function showSponsor()
    {
        return view('auth.sponsor');
    }

    public function validateSponsor(Request $request)
    {
        $request->validate([
            'sponsor_id' => 'required|string',
        ]);

        $sponsor = \App\Models\User::where('username', $request->sponsor_id)
            ->orWhere('id', $request->sponsor_id) // Allow ID as well if username is numeric or just in case
            ->first();

        if ($sponsor && $sponsor->is_active) {
            return response()->json([
                'success' => true,
                'name' => $sponsor->name,
                'username' => $sponsor->username,
                'status' => 'Active',
            ]);
        }

        return response()->json(['message' => 'Invalid Sponsor ID.'], 404);
    }

    public function showRegister(Request $request)
    {
        $sponsorId = $request->query('sponsor');
        $sponsorName = '';
        if ($sponsorId) {
            $sponsor = \App\Models\User::where('username', $sponsorId)->first();
            if ($sponsor) {
                $sponsorName = $sponsor->name;
            }
        }
        return view('auth.register', compact('sponsorId', 'sponsorName'));
    }

    public function register(Request $request)
    {
        \Illuminate\Support\Facades\Log::info("Reg: Request received");
        $step1 = microtime(true);
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'address' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|digits:6',
            'terms' => 'accepted',
        ]);
        $step2 = microtime(true);
        \Illuminate\Support\Facades\Log::info("Reg: Validation took " . ($step2 - $step1) . "s");

        // Auto-generate Sponsor ID
        $username = $this->generateSponsorId($request->name);
        $step3 = microtime(true);
        \Illuminate\Support\Facades\Log::info("Reg: ID Gen took " . ($step3 - $step2) . "s");

        // Determine sponsor: only use provided sponsor_id (username) if present
        $sponsorValue = $request->sponsor_id ?? null;

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'address' => $request->address,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'username' => $username,
            'sponsor_id' => $sponsorValue,
            'role' => 'user',
            'is_active' => true,
        ]);
        $step4 = microtime(true);
        \Illuminate\Support\Facades\Log::info("Reg: DB Create took " . ($step4 - $step3) . "s");

        // Auto-login the newly registered user so they remain authenticated
        try {
            \Illuminate\Support\Facades\Auth::login($user);
            $request->session()->regenerate();
        } catch (\Throwable $e) {
            // If login fails for any reason, log it but continue returning success
            \Illuminate\Support\Facades\Log::warning('Auto-login failed: ' . $e->getMessage());
        }

        $step5 = microtime(true);
        \Illuminate\Support\Facades\Log::info("Reg: Total took " . ($step5 - $step1) . "s");

        // Redirect to success page with Sponsor ID in URL
        $successUrl = route('auth.success') . '?id=' . urlencode($username) . '&name=' . urlencode($user->name);
        
        return response()->json([
            'success' => true,
            'redirect_url' => $successUrl,
            'sponsor_id' => $username,
            'user' => $user,
        ]);
    }

    private function generateSponsorId($name)
    {
        $cleanedName = preg_replace('/[^a-zA-Z]/', '', $name);
        $prefix = strtoupper(substr($cleanedName, 0, 3));
        if (strlen($prefix) < 3) {
            $prefix = str_pad($prefix, 3, 'X');
        }

        // Try a few times with random numbers
        for ($i = 0; $i < 5; $i++) {
            $sponsorId = $prefix . rand(1000, 9999);
            if (!\App\Models\User::where('username', $sponsorId)->exists()) {
                return $sponsorId;
            }
        }

        // Fallback to a longer random number if collisions persist
        return $prefix . rand(10000, 99999);
    }

    public function success()
    {
        return view('auth.success');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->username;
        $loginField = 'username'; // Default to Sponsor ID

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
        } elseif (is_numeric($identifier) && strlen($identifier) === 10) {
            $loginField = 'mobile';
        }
        
        $credentials = [
            $loginField => $identifier,
            'password' => $request->password,
            'is_active' => true
        ];

        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Invalid credentials or inactive account.',
        ])->onlyInput('username');
    }
    
    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function verifyUser(Request $request)
    {
        $request->validate(['identity' => 'required']);
        
        $user = \App\Models\User::where('email', $request->identity)
            ->orWhere('mobile', $request->identity)
            ->orWhere('username', $request->identity)
            ->first();

        if ($user) {
            return response()->json(['success' => true, 'user_id' => $user->id]);
        }
        
        return response()->json(['success' => false, 'message' => 'Account not found.']);
    }

    public function sendReset(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|confirmed',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'redirect_url' => route('auth.login')]);
    }
}
