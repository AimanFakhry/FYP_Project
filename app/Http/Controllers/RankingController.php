<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    /**
     * Display the global leaderboard.
     */
    public function index()
    {
        // 1. Get paginated list of users, sorted by XP (Highest first)
        // We exclude admins from the competition
        $rankings = User::where('is_admin', false)
                        ->orderByDesc('exptotal')
                        ->paginate(20); // 20 per page

        // 2. Calculate the current user's specific rank
        // (Count how many people have MORE xp than the current user, then add 1)
        $currentUser = Auth::user();
        $userRank = User::where('is_admin', false)
                        ->where('exptotal', '>', $currentUser->exptotal)
                        ->count() + 1;

        return view('users.ranking.index', compact('rankings', 'currentUser', 'userRank'));
    }
}