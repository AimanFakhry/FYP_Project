<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lessonGroups()
    {
        return $this->hasMany(LessonGroup::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, LessonGroup::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}