<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('achievement_user')->truncate();
        DB::table('achievements')->truncate();
        Schema::enableForeignKeyConstraints();

        $cpp = Course::where('name', 'C++')->first();
        $php = Course::where('name', 'PHP')->first();
        $js = Course::where('name', 'JavaScript')->first();

        $achievements = [
            // Original 4 (kept for continuity)
            [
                'name' => 'First Steps',
                'description' => 'Completed your very first lesson.',
                'course_id' => null, // Global badge (uses default trophy icon)
            ],
            [
                'name' => 'C++ Novice',
                'description' => 'Completed the C++ Basics module.',
                'course_id' => $cpp?->id, // Uses C++ icon
            ],
            [
                'name' => 'PHP Novice',
                'description' => 'Completed the PHP Basics module.',
                'course_id' => $php?->id, // Uses PHP icon
            ],
            [
                'name' => 'JS Novice',
                'description' => 'Completed the JavaScript Basics module.',
                'course_id' => $js?->id, // Uses JS icon
            ],
            // New 16 Achievements (expanded for progression)
            [
                'name' => 'C++ Introduction Master',
                'description' => 'Mastered the basics of C++ introduction and first programs.',
                'course_id' => $cpp?->id,
            ],
            [
                'name' => 'C++ Variable Expert',
                'description' => 'Proficient in C++ data types, variables, and constants.',
                'course_id' => $cpp?->id,
            ],
            [
                'name' => 'C++ Operator Guru',
                'description' => 'Skilled in using C++ operators and expressions.',
                'course_id' => $cpp?->id,
            ],
            [
                'name' => 'C++ Control Flow Champion',
                'description' => 'Mastered C++ control structures like loops and conditionals.',
                'course_id' => $cpp?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'C++ Functions Pro',
                'description' => 'Expert in defining and using functions in C++.',
                'course_id' => $cpp?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'PHP Introduction Master',
                'description' => 'Mastered PHP basics and syntax.',
                'course_id' => $php?->id,
            ],
            [
                'name' => 'PHP Variable Expert',
                'description' => 'Proficient in PHP variables and data types.',
                'course_id' => $php?->id,
            ],
            [
                'name' => 'PHP Operator Guru',
                'description' => 'Skilled in PHP operators and expressions.',
                'course_id' => $php?->id,
            ],
            [
                'name' => 'PHP Web Concepts Champion',
                'description' => 'Mastered PHP for web development basics.',
                'course_id' => $php?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'PHP Database Pro',
                'description' => 'Expert in PHP database interactions.',
                'course_id' => $php?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'JavaScript Introduction Master',
                'description' => 'Mastered JavaScript fundamentals and basics.',
                'course_id' => $js?->id,
            ],
            [
                'name' => 'JavaScript Variable Expert',
                'description' => 'Proficient in JavaScript variables and data types.',
                'course_id' => $js?->id,
            ],
            [
                'name' => 'JavaScript Operator Guru',
                'description' => 'Skilled in JavaScript operators and expressions.',
                'course_id' => $js?->id,
            ],
            [
                'name' => 'JavaScript DOM Champion',
                'description' => 'Mastered manipulating the DOM with JavaScript.',
                'course_id' => $js?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'JavaScript Events Pro',
                'description' => 'Expert in handling events in JavaScript.',
                'course_id' => $js?->id, // Assuming future modules; assign when added
            ],
            [
                'name' => 'JavaScript Frameworks Novice',
                'description' => 'Introduced to JavaScript frameworks and libraries.',
                'course_id' => $js?->id, // Assuming future modules; assign when added
            ],
            // Global Achievements (for overall progress)
            [
                'name' => 'Code Explorer',
                'description' => 'Completed lessons across multiple programming languages.',
                'course_id' => null,
            ],
            [
                'name' => 'Master Coder',
                'description' => 'Completed all modules in one course.',
                'course_id' => null,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}