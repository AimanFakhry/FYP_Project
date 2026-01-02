<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonGroup;
use Illuminate\Http\Request;

class LessonGroupController extends Controller
{
    public function create(Course $course)
    {
        return view('admin.lesson_groups.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $course->lessonGroups()->create($request->all());

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'Group added successfully.');
    }

    public function edit(LessonGroup $group)
    {
        return view('admin.lesson_groups.edit', compact('group'));
    }

    public function update(Request $request, LessonGroup $group)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $group->update($request->all());

        return redirect()->route('admin.courses.show', $group->course)
                         ->with('success', 'Group updated successfully.');
    }

    public function destroy(LessonGroup $group)
    {
        $course = $group->course;
        $group->delete();

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'Group deleted successfully.');
    }
}