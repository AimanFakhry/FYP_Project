<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application's user dashboard.
     */
    public function index() // This line is now fixed
    {
        // This method points to the new view location
        return view('users.dashboard');
    }
}

