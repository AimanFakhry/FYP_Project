<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the application admin dashboard with stats.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Get Total Users (Excluding Admins)
        $totalUsers = User::where('is_admin', false)->count();

        // 2. Get Active Course Count
        $activeCourses = Course::count();

        // 3. Get Recent Users (New)
        $recentUsers = User::where('is_admin', false)
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();

        // 4. Get Course Distribution Data for the Chart (Optimized Query)
        // Updated to join through 'lesson_groups' since 'lessons' don't contain 'course_id' anymore.
        // Added join to 'users' and filter to exclude admins from the count.
        $courseStats = DB::table('courses')
            ->leftJoin('lesson_groups', 'courses.id', '=', 'lesson_groups.course_id')
            ->leftJoin('lessons', 'lesson_groups.id', '=', 'lessons.lesson_group_id')
            ->leftJoin('lesson_user', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->leftJoin('users', 'lesson_user.user_id', '=', 'users.id')
            ->where('users.is_admin', false) // Exclude admins from the learner count
            ->select('courses.name', DB::raw('COUNT(DISTINCT lesson_user.user_id) as user_count'))
            ->groupBy('courses.id', 'courses.name')
            ->orderByDesc('user_count')
            ->get();
        
        // Extract data for the chart
        $courseLabels = $courseStats->pluck('name');
        $courseCounts = $courseStats->pluck('user_count');
        
        // Professional Color Palette
        $baseColors = ['#4F46E5', '#0EA5E9', '#8B5CF6', '#10B981', '#F59E0B'];
        $courseColors = $courseLabels->map(function ($item, $key) use ($baseColors) {
            return $baseColors[$key % count($baseColors)];
        });

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeCourses',
            'recentUsers',
            'courseLabels',
            'courseCounts',
            'courseColors'
        ));
    }
}