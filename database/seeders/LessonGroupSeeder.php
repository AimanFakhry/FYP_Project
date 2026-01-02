<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\LessonGroup;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LessonGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate all related tables (children first)
        Schema::disableForeignKeyConstraints();
        DB::table('lesson_user')->truncate();
        DB::table('lessons')->truncate();
        DB::table('lesson_groups')->truncate();
        Schema::enableForeignKeyConstraints();

        // Find courses
        $cpp = Course::where('name', 'C++')->first();
        $php = Course::where('name', 'PHP')->first();
        $js = Course::where('name', 'JavaScript')->first();

        // --- Seed C++ Groups ---
        if ($cpp) {
            LessonGroup::create([
                'course_id' => $cpp->id,
                'title' => 'Part 1: The Basics',
                'order' => 1,
            ]);
            LessonGroup::create([
                'course_id' => $cpp->id,
                'title' => 'Part 2: Control Flow',
                'order' => 2,
            ]);
        }

        // --- Seed PHP Groups ---
        if ($php) {
            LessonGroup::create([
                'course_id' => $php->id,
                'title' => 'Part 1: PHP Fundamentals',
                'order' => 1,
            ]);
            LessonGroup::create([
                'course_id' => $php->id,
                'title' => 'Part 2: Web Concepts',
                'order' => 2,
            ]);
        }

        // --- Seed JavaScript Groups ---
        if ($js) {
            LessonGroup::create([
                'course_id' => $js->id,
                'title' => 'Part 1: JS Fundamentals',
                'order' => 1,
            ]);
            LessonGroup::create([
                'course_id' => $js->id,
                'title' => 'Part 2: The DOM',
                'order' => 2,
            ]);
        }
    }
}