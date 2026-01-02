<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // 1. Add the new column, making it nullable
            $table->foreignId('achievement_id')
                  ->nullable()
                  ->after('activity_type'); // Position it after the activity_data column

            // 2. Add the foreign key constraint
            $table->foreign('achievement_id')
                  ->references('id')
                  ->on('achievements')
                  ->nullOnDelete(); // If an achievement is deleted, set this to null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['achievement_id']);
            
            // Then drop the column
            $table->dropColumn('achievement_id');
        });
    }
};