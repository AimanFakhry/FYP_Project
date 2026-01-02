<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This is the new table for your groups and titles.
     */
    public function up(): void
    {
        Schema::create('lesson_groups', function (Blueprint $table) {
            $table->id(); // This is the 'group id'
            
            // This is the 'course type' (links to the courses table)
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            
            $table->string('title'); // This is the 'title'
            
            // This is the 'part' number (for sorting)
            $table->integer('order')->default(1); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_groups');
    }
};