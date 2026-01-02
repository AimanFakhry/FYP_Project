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
        Schema::table('users', function (Blueprint $table) {
            // For identifying admins (used in your controllers)
            $table->boolean('is_admin')->default(false)->after('password');
            
            // For gamification/leaderboard (used in your controllers/dashboard)
            $table->integer('exptotal')->default(0)->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'exptotal']);
        });
    }
};
