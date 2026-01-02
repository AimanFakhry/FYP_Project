<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LessonFillInTheBlank;
use Illuminate\Support\Facades\Auth;

class DailyChallengeController extends Controller
{
    /**
     * Show the daily challenge page with 5 random questions.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Check Eligibility: Must have completed at least 5 fill-in-the-blank lessons
        // We filter the completed lessons by the activity type 'fill_in_the_blank'
        $completedFillInTheBlankCount = $user->completedLessons()
            ->where('activity_type', 'fill_in_the_blank')
            ->count();

        if ($completedFillInTheBlankCount < 5) {
             return view('users.daily.index', [
                'questions' => collect(),
                'notEligible' => true,
                'completedCount' => $completedFillInTheBlankCount,
                'requiredCount' => 5,
                'alreadyCompleted' => false
            ]);
        }

        // 2. Check if user already completed the daily challenge today
        if ($user->last_daily_at && $user->last_daily_at->isToday()) {
            return view('users.daily.index', [
                'questions' => collect(),
                'alreadyCompleted' => true,
                'notEligible' => false
            ]);
        }

        // 3. Fetch 5 random questions FROM COMPLETED LESSONS only
        // Get IDs of lessons the user has completed
        $completedLessonIds = $user->completedLessons()->pluck('lessons.id');

        $questions = LessonFillInTheBlank::whereIn('lesson_id', $completedLessonIds)
                        ->with('lesson.lessonGroup.course')
                        ->inRandomOrder()
                        ->take(5)
                        ->get();

        return view('users.daily.index', [
            'questions' => $questions,
            'alreadyCompleted' => false,
            'notEligible' => false
        ]);
    }

    /**
     * Check the submitted answers.
     */
    public function check(Request $request)
    {
        $user = Auth::user();

        // Prevent resubmission if already done today
        if ($user->last_daily_at && $user->last_daily_at->isToday()) {
            return redirect()->route('daily.index');
        }

        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:lesson_fill_in_the_blanks,id',
        ]);

        // Fetch all questions at once
        $questions = LessonFillInTheBlank::whereIn('id', $data['question_ids'])->get();

        $score = 0;
        $total = count($data['question_ids']);
        $results = [];
        $xpEarned = 0;

        foreach ($data['question_ids'] as $index => $id) {
            $question = $questions->find($id);
            $userAnswer = trim($data['answers'][$index] ?? '');
            
            // Basic case-insensitive comparison
            $isCorrect = strtolower($userAnswer) === strtolower($question->answer);

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $question, 
                'user_answer' => $userAnswer,
                'correct_answer' => $question->answer,
                'is_correct' => $isCorrect,
            ];
        }

        // Award XP and mark as completed for today
        if ($score > 0) {
            $xpEarned = $score * 50;
            $user->increment('exptotal', $xpEarned);
        }
        
        $user->update(['last_daily_at' => now()]);

        return view('users.daily.results', compact('results', 'score', 'total', 'xpEarned', 'questions'));
    }
}