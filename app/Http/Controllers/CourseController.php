<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function show(Request $request, $courseName, $lessonId)
{
    $user = Auth::user();
    if (!$user) {
        abort(403, 'Unauthorized');
    }

    $course = Course::where('name', $courseName)->firstOrFail();

    // Find the current lesson and eager load its specific activity data
    $currentLesson = Lesson::where('id', $lessonId)
        ->whereHas('lessonGroup', fn($q) => $q->where('course_id', $course->id))
        ->with(['fillInTheBlank', 'sandbox', 'textOnly']) // Load the specific data for this lesson
        ->firstOrFail();

    // Get all lessons for the sidebar, ordered by lesson_groups.order then lessons.order
    $allLessons = $course->lessons()
        ->join('lesson_groups', 'lessons.lesson_group_id', '=', 'lesson_groups.id')
        ->select('lessons.id', 'lessons.title', 'lessons.lesson_group_id', 'lessons.order', 'lessons.activity_type', 'lessons.achievement_id')
        ->with(['lessonGroup', 'fillInTheBlank', 'sandbox', 'textOnly']) 
        ->orderBy('lesson_groups.order')
        ->orderBy('lessons.order')
        ->get();

    // Get the ordered list of unique lesson_group_ids (based on lesson_groups.order)
    $lessonGroupsOrdered = $allLessons->pluck('lesson_group_id')->unique()->values();

    $completedLessonIds = $user->completedLessons()->pluck('lessons.id')->toArray();

    // Prepare sidebar lessons with statuses and part numbers
    $sidebarLessons = $allLessons->map(function ($lesson) use ($currentLesson, $completedLessonIds, $allLessons, $lessonGroupsOrdered) {
        $lesson->status = $this->getLessonStatus($lesson, $currentLesson, $completedLessonIds, $allLessons);
        // Assign sequential part number (1-based) based on the order of lesson_group_id in the course
        $lesson->part_number = $lessonGroupsOrdered->search($lesson->lesson_group_id) + 1;
        return $lesson;
    });

    // For the progress bar
    $progressLessons = $allLessons->where('lesson_group_id', $currentLesson->lesson_group_id)
        ->map(function ($lesson) use ($currentLesson, $completedLessonIds, $allLessons) {
            $lesson->status = $this->getLessonStatus($lesson, $currentLesson, $completedLessonIds, $allLessons);
            return $lesson;
        })->values();

    // Define activity data based on the relationship instead of JSON
    $activityData = null;
    if ($currentLesson->activity_type === 'fill_in_the_blank') {
        $activityData = $currentLesson->fillInTheBlank;
    } elseif ($currentLesson->activity_type === 'sandbox') {
        $activityData = $currentLesson->sandbox;
    } elseif ($currentLesson->activity_type === 'text_only') {
        $activityData = $currentLesson->textOnly;
    }
    
    // Ensure we pass an object even if null to prevent view errors
    $activityData = $activityData ?? (object)[];

    // Assign part_number to currentLesson for the progress bar header
    $currentLesson->part_number = $lessonGroupsOrdered->search($currentLesson->lesson_group_id) + 1;

    return view('users.courses.lesson', compact(
        'course',
        'currentLesson',
        'sidebarLessons',
        'progressLessons',
        'activityData'
    ));
}

    /**
     * Determine the status of a lesson within its group.
     */
    private function getLessonStatus($lesson, $completedLessonIds, $groupLessons)
    {
        // Completed if in lesson_user table
        if (in_array($lesson->id, $completedLessonIds)) {
            return 'completed';
        }

        // Unlocked logic: The next incomplete lesson(s) in the group
        // - If no lessons are completed in the group, unlock the first one.
        // - Otherwise, unlock the next one after the last completed.
        $completedInGroup = $groupLessons->filter(fn($l) => in_array($l->id, $completedLessonIds));
        
        if ($completedInGroup->isEmpty()) {
            // No completions: Unlock the first lesson in the group
            $firstInGroup = $groupLessons->first();
            if ($lesson->id == $firstInGroup->id) {
                return 'unlocked';
            }
        } else {
            // Unlock the next lesson after the last completed
            $lastCompleted = $completedInGroup->last();
            $nextLesson = $groupLessons->where('order', '>', $lastCompleted->order)->first();
            if ($nextLesson && $lesson->id == $nextLesson->id) {
                return 'unlocked';
            }
        }

        // Otherwise, locked
        return 'locked';
    }
}