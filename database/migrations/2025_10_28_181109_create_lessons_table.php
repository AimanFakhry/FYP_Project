<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('lesson_group_id')
                  ->constrained('lesson_groups')
                  ->onDelete('cascade');
            
            $table->string('title');
            $table->longText('content')->nullable(); 
            $table->integer('order')->default(0); 
            $table->string('activity_type')->default('text_only');
            
            // REMOVED: $table->json('activity_data'); 
            // We now use separate tables for this data.
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};