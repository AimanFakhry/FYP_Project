<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch users who are NOT admins
        $topLearners = User::where('is_admin', false) // Add this line
                             ->orderByDesc('exptotal')
                             ->take(10)
                             ->get();

        return view('welcome', compact('topLearners'));
    }
}