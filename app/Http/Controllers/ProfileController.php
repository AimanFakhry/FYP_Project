<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;

class ProfileController extends Controller
{
    /**
     * Show the user's profile screen.
     */
    public function show()
    {
        $user = Auth::user();

        // Redirect admins to their specific profile page
        if ($user->is_admin) {
            return redirect()->route('admin.profile.show');
        }
        
        $level = floor($user->exptotal / 1000) + 1;
        $leaderboardRank = $user->getLeaderboardRank();
        
        $courses = Course::withCount('lessons')->get();
        $courseProgress = [];

        foreach ($courses as $course) {
            $totalLessons = $course->lessons_count;
            
            $completedCount = $user->completedLessons()
                ->whereHas('lessonGroup', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->count();

            $percentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

            $courseProgress[] = [
                'id' => $course->id,
                'name' => $course->name,
                'progress' => $percentage
            ];
        }

        $achievements = $user->achievements;

        return view('users.profile', compact(
            'user', 
            'level', 
            'leaderboardRank', 
            'courseProgress', 
            'achievements'
        ));
    }

    /**
     * Show the ADMIN profile.
     */
    public function showAdmin()
    {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            return redirect()->route('profile.show');
        }

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->take(5)
            ->get();

        return view('admin.profile.show', compact('user', 'sessions'));
    }

    /**
     * Show the edit profile form (Users Only).
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('admin.profile.show');
        }

        return view('users.profile_edit', compact('user'));
    }

    /**
     * Update the REGULAR USER'S profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('admin.profile.show');
        }

        // validation for users includes theme and avatar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'theme' => 'required|in:cheerful,spacy,techy',
            'avatar' => 'required|in:cat,dog,panda,fox,rabbit,lion',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->theme = $request->theme;
        $user->avatar = $request->avatar;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the ADMIN'S profile.
     */
    public function updateAdmin(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_admin) {
            abort(403);
        }

        // Validation for admin (No theme/avatar required)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Admin profile updated successfully!');
    }

    public function resetCourse(Request $request)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);
        $user = Auth::user();
        if ($user->is_admin) return redirect()->back();

        $course = Course::findOrFail($request->input('course_id'));
        $lessonIds = $course->lessons()->pluck('lessons.id');
        $user->completedLessons()->detach($lessonIds);

        return redirect()->back()->with('success', "Progress for {$course->name} has been reset.");
    }
}