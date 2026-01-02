<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Display the course details and its lessons.
     * This is the "Manage Lessons" page for a specific course.
     */
    public function show(Course $course)
    {
        // Load the course with its groups and lessons for display
        $course->load(['lessonGroups.lessons' => function($query) {
            $query->orderBy('order', 'asc');
        }]);

        return view('admin.courses.show', compact('course'));
    }
}