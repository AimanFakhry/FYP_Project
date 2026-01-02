<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            
            // Replaced icon_path with course_id
            // This allows the achievement to use the course's icon dynamically
            $table->foreignId('course_id')
                  ->nullable() // Nullable for general achievements (like "First Steps")
                  ->constrained('courses')
                  ->nullOnDelete();
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('achievements');
    }
};