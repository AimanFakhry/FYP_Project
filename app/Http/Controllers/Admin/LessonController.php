<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonGroup;
use App\Models\Lesson;
use App\Models\Achievement;
use App\Models\LessonTextOnly;       // <-- NEW
use App\Models\LessonFillInTheBlank; // <-- NEW
use App\Models\LessonSandbox;        // <-- NEW
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function create(LessonGroup $group)
    {
        $achievements = Achievement::all();
        return view('admin.lessons.create', compact('group', 'achievements'));
    }

    public function store(Request $request, LessonGroup $group)
    {
        // 1. Validate Base Lesson Data
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
            'activity_type' => 'required|in:text_only,fill_in_the_blank,sandbox',
            'content' => 'nullable|string', // Basic intro content
            'achievement_id' => 'nullable|exists:achievements,id',
        ]);

        // 2. Create Base Lesson
        $lesson = new Lesson($request->only(['title', 'order', 'activity_type', 'content', 'achievement_id']));
        $lesson->lesson_group_id = $group->id;
        $lesson->save();

        // 3. Handle Specific Activity Data
        switch ($request->activity_type) {
            case 'text_only':
                $request->validate(['text_only_content' => 'required|string']);
                LessonTextOnly::create([
                    'lesson_id' => $lesson->id,
                    'content' => $request->text_only_content,
                ]);
                break;

            case 'fill_in_the_blank':
                $request->validate([
                    'fib_question' => 'required|string',
                    'fib_answer' => 'required|string',
                ]);
                LessonFillInTheBlank::create([
                    'lesson_id' => $lesson->id,
                    'question' => $request->fib_question,
                    'answer' => $request->fib_answer,
                ]);
                break;

            case 'sandbox':
                $request->validate(['sandbox_code' => 'nullable|string']);
                LessonSandbox::create([
                    'lesson_id' => $lesson->id,
                    'starting_code' => $request->sandbox_code,
                ]);
                break;
        }

        return redirect()->route('admin.courses.show', $group->course)
                         ->with('success', 'Lesson added successfully.');
    }

    public function edit(Lesson $lesson)
    {
        $achievements = Achievement::all();
        // Load specific relationships to populate the form
        $lesson->load(['textOnly', 'fillInTheBlank', 'sandbox']);
        return view('admin.lessons.edit', compact('lesson', 'achievements'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        // 1. Validate Base Data
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
            'activity_type' => 'required|in:text_only,fill_in_the_blank,sandbox',
            'content' => 'nullable|string',
            'achievement_id' => 'nullable|exists:achievements,id',
        ]);

        // 2. Update Base Lesson
        $lesson->update($request->only(['title', 'order', 'activity_type', 'content', 'achievement_id']));

        // 3. Handle Specific Activity Data (Update or Create)
        switch ($request->activity_type) {
            case 'text_only':
                $request->validate(['text_only_content' => 'required|string']);
                LessonTextOnly::updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    ['content' => $request->text_only_content]
                );
                // Clean up others if switching types (optional but good practice)
                $lesson->fillInTheBlank()->delete();
                $lesson->sandbox()->delete();
                break;

            case 'fill_in_the_blank':
                $request->validate([
                    'fib_question' => 'required|string',
                    'fib_answer' => 'required|string',
                ]);
                LessonFillInTheBlank::updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    ['question' => $request->fib_question, 'answer' => $request->fib_answer]
                );
                $lesson->textOnly()->delete();
                $lesson->sandbox()->delete();
                break;

            case 'sandbox':
                $request->validate(['sandbox_code' => 'nullable|string']);
                LessonSandbox::updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    ['starting_code' => $request->sandbox_code]
                );
                $lesson->textOnly()->delete();
                $lesson->fillInTheBlank()->delete();
                break;
        }

        return redirect()->route('admin.courses.show', $lesson->lessonGroup->course)
                         ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        $course = $lesson->lessonGroup->course;
        $lesson->delete(); // Cascading deletes in DB should handle related tables

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'Lesson deleted successfully.');
    }
}