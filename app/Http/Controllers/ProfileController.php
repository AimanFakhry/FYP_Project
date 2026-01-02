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
        
        // --- Calculate Real Data from DB ---
        
        $level = floor($user->exptotal / 1000) + 1;
        $leaderboardRank = $user->getLeaderboardRank();
        
        // Calculate Progress for each Course
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

        // Note: Avatars are defined in the view, not here.

        return view('users.profile', compact(
            'user', 
            'level', 
            'leaderboardRank', 
            'courseProgress', 
            'achievements'
        ));
    }

    /**
     * Reset progress for a specific course.
     */
    public function resetCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->input('course_id'));

        $lessonIds = $course->lessons()->pluck('lessons.id');
        $user->completedLessons()->detach($lessonIds);

        return redirect()->back()->with('success', "Progress for {$course->name} has been reset.");
    }

    /**
     * Show the edit profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        // Avatars defined in view
        return view('users.profile_edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // 2. Add Theme/Avatar validation ONLY for non-admins (regular users)
        if (!$user->is_admin) {
            $rules['theme'] = 'required|in:cheerful,spacy,techy';
            $rules['avatar'] = 'required|in:cat,dog,panda,fox,rabbit,lion';
        }

        // 3. Add Password validation ONLY if fields are provided
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // 4. Update User Data
        $user->name = $request->name;
        $user->email = $request->email;

        if (!$user->is_admin) {
            $user->theme = $request->theme;
            $user->avatar = $request->avatar;
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // 5. Redirect based on role
        if ($user->is_admin) {
            return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully!');
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function showAdmin()
    {
        $user = Auth::user();
        
        // Fetch recent login sessions for the security log
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->take(5)
            ->get();

        return view('admin.profile.show', compact('user', 'sessions'));
    }
}