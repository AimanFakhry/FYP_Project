<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create()
    {
        return view('auth.reset');
    }

    /**
     * Handle an incoming new password request with security token check.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'security_token' => 'required|string', // <-- Validate token input
            'password' => 'required|confirmed|min:8',
        ]);

        // Find the user by their email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists AND if the token matches
        if ($user && $user->reset_token === $request->security_token) {
            
            // Update password
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->save();

            return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
        }

        // Failed: Token didn't match (or user somehow not found despite valid email)
        return back()->withErrors(['security_token' => 'Invalid security token provided.']);
    }
}