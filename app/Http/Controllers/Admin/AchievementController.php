<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Course;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::with('course')->get();
        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.achievements.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        Achievement::create($request->all());

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Achievement created successfully.');
    }

    public function edit(Achievement $achievement)
    {
        $courses = Course::all();
        return view('admin.achievements.edit', compact('achievement', 'courses'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $achievement->update($request->all());

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Achievement updated successfully.');
    }

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Achievement deleted successfully.');
    }
}