<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        Schema::enableForeignKeyConstraints();

        // C++ (Simple Monitor Icon)
        Course::create([
            'name' => 'C++',
            'description' => 'Dive deep into the world of high-performance computing. Master memory management, pointers, and object-oriented architecture to build powerful software systems, game engines, and resource-intensive applications used by millions.',
            'icon_svg' => '<svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'color_class' => 'bg-blue-600'
        ]);

        // PHP (Simple Code Brackets Icon)
        Course::create([
            'name' => 'PHP',
            'description' => 'Become a master of the server-side. Learn to build secure, dynamic web applications, manage databases, and create robust APIs. From basic scripts to enterprise-level applications with Laravel, this path covers it all.',
            'icon_svg' => '<svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
            'color_class' => 'bg-indigo-600'
        ]);

        // JavaScript (Simple Chip Icon)
        Course::create([
            'name' => 'JavaScript',
            'description' => 'Unlock the full potential of the interactive web. From manipulating the DOM to asynchronous programming and modern frameworks, master the language that powers the frontend of the internet.',
            'icon_svg' => '<svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>',
            'color_class' => 'bg-yellow-500'
        ]);
    }
}