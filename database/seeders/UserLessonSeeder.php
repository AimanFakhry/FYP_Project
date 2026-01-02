<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserLessonSeeder extends Seeder
{
    public function run()
    {
        // Fetch all users and lessons
        $users = User::where('is_admin', false)->get(); // Only non-admin users
        $lessons = Lesson::with('lessonGroup')->orderBy('lesson_group_id')->orderBy('order')->get(); // Order by group and lesson order

        $totalLessons = $lessons->count();

        foreach ($users as $user) {
            // Calculate number of lessons to complete based on exptotal (scale: e.g., 1 exp = 0.1 lessons)
            $lessonsToComplete = min($totalLessons, (int)($user->exptotal * 0.1)); // Cap at total lessons

            // Get the first N lessons (sequential progression)
            $completedLessons = $lessons->take($lessonsToComplete);

            foreach ($completedLessons as $lesson) {
                // Check if already completed (avoid duplicates)
                $exists = DB::table('lesson_user')
                    ->where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->exists();

                if (!$exists) {
                    DB::table('lesson_user')->insert([
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'completed_at' => Carbon::now()->subDays(rand(1, 30)), // Random past date for realism
                    ]);
                }
            }
        }
    }
}