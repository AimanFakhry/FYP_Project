<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningPathController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        // Fetch all courses with the count of their lessons
        $courses = Course::withCount('lessons')->get();
        
        $progress = [];

        foreach ($courses as $course) {
            $totalLessons = $course->lessons_count;
            
            // Calculate completed lessons count for this user and course
            // We verify completion by checking lessons associated with the course via LessonGroups
            $completedCount = $user->completedLessons()
                ->whereHas('lessonGroup', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->count();
            
            // Calculate percentage (avoid division by zero)
            $percentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
            
            // Store in array using lowercase name as key (e.g., 'c++', 'php', 'javascript')
            $progress[strtolower($course->name)] = $percentage;
        }
        
        return view('users.courses.index', compact('courses', 'progress'));
    }

    
    public function show(Course $course)
    {
        $user = Auth::user();

        // 1. Get all lesson IDs for the entire course, fixing ambiguity
        $allLessonIds = $course->lessons()->select('lessons.id')->pluck('lessons.id');

        // 2. Get all lesson groups, eager-loading their lessons
        $lessonGroups = $course->lessonGroups()->with('lessons')->get();

        // 3. Get user's completed lesson IDs
        $completedLessonIds = $user->completedLessons()
                                  ->whereIn('lesson_id', $allLessonIds)
                                  ->pluck('lesson_id')
                                  ->toBase();

        // 4. Determine status for each lesson within its group
        $previousLessonCompleted = true; // Start unlocked

        foreach ($lessonGroups as $group) {
            foreach ($group->lessons as $lesson) {
                
                $status = 'locked'; // Default to locked

                if ($completedLessonIds->contains($lesson->id)) {
                    $status = 'completed';
                    $previousLessonCompleted = true; // This lesson is done
                } elseif ($previousLessonCompleted) {
                    $status = 'unlocked';
                    $previousLessonCompleted = false; // Next lesson will be locked
                }
                
                $lesson->status = $status; // Attach the status to the lesson object
            }
        }

        return view('users.courses.show', [
            'course' => $course,
            'lessonGroups' => $lessonGroups // Pass the new grouped data
        ]);
    }
}