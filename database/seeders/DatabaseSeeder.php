<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call the seeders in the desired order
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            AchievementSeeder::class,
            LessonSeeder::class,
            UserLessonSeeder::class,
            // LessonUserSeeder::class has been removed so users start with empty progress
        ]);
    }
}