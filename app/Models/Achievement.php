<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'course_id'];

    /**
     * Get the course associated with this achievement.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Helper to get the icon SVG. 
     */
    public function getIconAttribute()
    {
        if ($this->course) {
            return $this->course->icon_svg;
        }
        
        // Detailed Gold Medal SVG for generic achievements
        return '<svg viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full"><path d="M256 0L319 128H193L256 0Z" fill="#EAB308"/><path d="M432 128H80V192C80 289.2 158.8 368 256 368C353.2 368 432 289.2 432 192V128Z" fill="#F59E0B"/><path d="M256 368V448H192V512H320V448H256" stroke="#B45309" stroke-width="32" stroke-linecap="round"/><path d="M256 160L280 216L336 224L296 264L304 320L256 296L208 320L216 264L176 224L232 216L256 160Z" fill="#FFFBEB"/></svg>';
    }
}