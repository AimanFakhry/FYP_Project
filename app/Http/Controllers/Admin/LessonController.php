<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonGroup;
use App\Models\Lesson;
use App\Models\Achievement;
use App\Models\LessonTextOnly;       // <-- Keep for other types if needed
use App\Models\LessonFillInTheBlank; // <-- Keep
use App\Models\LessonSandbox;        // <-- Keep
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
            'content' => 'nullable|string', // Basic intro content (used for non-text_only or as fallback)
            'achievement_id' => 'nullable|exists:achievements,id',
        ]);

        // 2. Create Base Lesson
        $lesson = new Lesson($request->only(['title', 'order', 'activity_type', 'content', 'achievement_id']));
        $lesson->lesson_group_id = $group->id;

        // 3. Handle Specific Activity Data and Adjust Content
        switch ($request->activity_type) {
            case 'text_only':
                $request->validate(['text_only_content' => 'required|string']);
                // Store full content in Lesson.content for text_only
                $lesson->content = $request->text_only_content;
                // No LessonTextOnly for text_only (stored in Lesson.content)
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

        $lesson->save(); // Save after adjustments

        return redirect()->route('admin.courses.show', $group->course)
                         ->with('success', 'Lesson added successfully.');
    }

    public function edit(Lesson $lesson)
    {
        $achievements = Achievement::all();
        // Load specific relationships (skip textOnly for text_only since content is in lesson)
        if ($lesson->activity_type !== 'text_only') {
            $lesson->load(['textOnly', 'fillInTheBlank', 'sandbox']);
        }
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

        // 3. Handle Specific Activity Data and Adjust Content
        switch ($request->activity_type) {
            case 'text_only':
                $request->validate(['text_only_content' => 'required|string']);
                // Update content in Lesson.content for text_only
                $lesson->content = $request->text_only_content;
                $lesson->save();
                // Clean up others if switching types
                $lesson->fillInTheBlank()->delete();
                $lesson->sandbox()->delete();
                // No LessonTextOnly to delete for text_only
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
                // Clean up others
                $lesson->textOnly()->delete();
                $lesson->sandbox()->delete();
                break;

            case 'sandbox':
                $request->validate(['sandbox_code' => 'nullable|string']);
                LessonSandbox::updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    ['starting_code' => $request->sandbox_code]
                );
                // Clean up others
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