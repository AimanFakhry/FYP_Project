<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_group_id',
        'title',
        'content',
        'order',
        'activity_type',
        'achievement_id',
    ];

    // Relationships to lesson_groups
    public function lessonGroup()
    {
        return $this->belongsTo(LessonGroup::class);
    }

    // Relationships to activity-specific tables
    public function fillInTheBlank()
    {
        return $this->hasOne(LessonFillInTheBlank::class); // Assumes model class is FillInTheBlank
    }

    public function sandbox()
    {
        return $this->hasOne(LessonSandbox::class); // Assumes model class is Sandbox
    }

    public function textOnly()
    {
        return $this->hasOne(LessonTextOnly::class); // Assumes model class is TextOnly
    }

    // Other relationships (e.g., for completions, if needed)
    public function completedUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_user')->withPivot('completed_at');
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

}