<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;

class LessonController extends Controller
{
    public function show(Request $request, $courseName, $lessonId)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $course = Course::where('name', $courseName)->firstOrFail();

        $currentLesson = Lesson::where('id', $lessonId)
            ->whereHas('lessonGroup', fn($q) => $q->where('course_id', $course->id))
            ->with(['fillInTheBlank', 'sandbox', 'textOnly'])
            ->firstOrFail();

        $allLessons = $course->lessons()
            ->select('lessons.id', 'lessons.title', 'lessons.lesson_group_id', 'lessons.order', 'lessons.activity_type', 'lessons.achievement_id')
            ->with(['lessonGroup', 'fillInTheBlank', 'sandbox', 'textOnly'])
            ->orderBy(\DB::raw('(SELECT `order` FROM lesson_groups WHERE lesson_groups.id = lessons.lesson_group_id)'))
            ->orderBy('lessons.order')
            ->get();

        $currentIndex = $allLessons->search(function($item) use ($currentLesson) {
            return $item->id === $currentLesson->id;
        });

        $currentLesson->global_order = ($currentIndex !== false) ? $currentIndex + 1 : '?';

        // Get lesson groups for this course, sorted by order
        $lessonGroupsForCourse = $course->lessonGroups()->orderBy('order')->get();

        // Assign sequential part numbers (1, 2, 3, etc.) based on row count
        $partNumbers = [];
        foreach ($lessonGroupsForCourse as $index => $group) {
            $partNumbers[$group->id] = $index + 1;
        }

        $completedLessonIds = $user->completedLessons()->pluck('lessons.id')->toArray();

        // Prepare sidebar lessons with corrected numbering
        $sidebarLessons = $allLessons->map(function ($lesson) use ($currentLesson, $completedLessonIds, $allLessons, $partNumbers) {
            $lesson->status = $this->getLessonStatus($lesson, $currentLesson, $completedLessonIds, $allLessons);
            $lesson->part_number = $partNumbers[$lesson->lesson_group_id] ?? 1;  // Sequential part number
            $lesson->lesson_number = $lesson->order;  // Use lessons.order directly
            return $lesson;
        });

        $progressLessons = $allLessons->where('lesson_group_id', $currentLesson->lesson_group_id)
            ->map(function ($lesson) use ($currentLesson, $completedLessonIds, $allLessons) {
                $lesson->status = $this->getLessonStatus($lesson, $currentLesson, $completedLessonIds, $allLessons);
                return $lesson;
            })->values();

        $activityData = null;
        if ($currentLesson->activity_type === 'fill_in_the_blank') {
            $activityData = $currentLesson->fillInTheBlank;
        } elseif ($currentLesson->activity_type === 'sandbox') {
            $activityData = $currentLesson->sandbox;
        } elseif ($currentLesson->activity_type === 'text_only') {
            $activityData = $currentLesson->textOnly;
        }
        
        $activityData = $activityData ?? (object)[];

        $currentLesson->part_number = $partNumbers[$currentLesson->lesson_group_id] ?? 1;

        return view('users.courses.lesson', compact(
            'course',
            'currentLesson',
            'sidebarLessons',
            'progressLessons',
            'activityData'
        ));
    }

    private function getLessonStatus($lesson, $currentLesson, $completedLessonIds, $allLessons)
{
    if (in_array($lesson->id, $completedLessonIds)) {
        return 'completed';
    }

    if ($lesson->id == $currentLesson->id) {
        return 'active';
    }

    $groupLessons = $allLessons->where('lesson_group_id', $lesson->lesson_group_id);
    $completedInGroup = $groupLessons->filter(fn($l) => in_array($l->id, $completedLessonIds));
    
    if ($completedInGroup->isEmpty()) {
        $firstInGroup = $groupLessons->first();
        if ($lesson->id == $firstInGroup->id) {
            // Check if previous group is fully completed
            $lessonGroupsOrdered = $allLessons->pluck('lesson_group_id')->unique()->sort()->values();
            $currentGroupIndex = $lessonGroupsOrdered->search($lesson->lesson_group_id);
            
            if ($currentGroupIndex > 0) {
                $previousGroupId = $lessonGroupsOrdered[$currentGroupIndex - 1];
                $previousGroupLessons = $allLessons->where('lesson_group_id', $previousGroupId);
                $allPreviousCompleted = $previousGroupLessons->every(fn($l) => in_array($l->id, $completedLessonIds));
                
                if (!$allPreviousCompleted) {
                    return 'locked';  // Previous group not fully completed
                }
            }
            
            return 'unlocked';  // First lesson in group unlocked if previous group is done
        }
    } else {
        $lastCompleted = $completedInGroup->last();
        $lastCompletedIndex = $allLessons->search(function($item) use ($lastCompleted) {
            return $item->id === $lastCompleted->id;
        });
        
        if ($lastCompletedIndex !== false && $lastCompletedIndex < $allLessons->count() - 1) {
             $nextGlobalLesson = $allLessons->get($lastCompletedIndex + 1);
             if ($nextGlobalLesson->id == $lesson->id) {
                 return 'unlocked';  // Next lesson in sequence unlocked
             }
        }
    }

    return 'locked';
}

    public function complete(Request $request)
{
    $request->validate([
        'lesson_id' => 'required|integer|exists:lessons,id',
    ]);

    $user = Auth::user();
    $lesson = Lesson::findOrFail($request->input('lesson_id'));

    $alreadyCompleted = $user->completedLessons()
                             ->where('lesson_id', $lesson->id)
                             ->exists();

    $achievementUnlocked = false;  // Declare at start

    if ($lesson->activity_type === 'fill_in_the_blank') {
        $correctAnswer = $lesson->fillInTheBlank->answer ?? '';
        $userAnswer = trim($request->input('answer', ''));

        if (strtolower($userAnswer) !== strtolower($correctAnswer)) {
            return redirect()->back()->with('error', 'Incorrect answer. Please try again.');
        }

        // Determine if this is the last lesson
        $course = $lesson->lessonGroup->course;
        $allLessons = $course->lessons()
            ->select('lessons.*')
            ->orderBy(\DB::raw('(SELECT `order` FROM lesson_groups WHERE lesson_groups.id = lessons.lesson_group_id)'))
            ->orderBy('lessons.order')
            ->get();
        
        $currentIndex = $allLessons->search(function($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        $isLastLesson = ($currentIndex === false || $currentIndex >= $allLessons->count() - 1);

        // Run rewards logic only if not already completed
        if (!$alreadyCompleted) {
            $user->completedLessons()->attach($lesson->id, ['completed_at' => now()]);

            // Only award XP if achievement is unlocked
            if ($lesson->achievement_id) {
                if (!$user->achievements()->where('achievement_id', $lesson->achievement_id)->exists()) {
                    $user->achievements()->attach($lesson->achievement_id);
                    $achievement = Achievement::find($lesson->achievement_id);
                    session()->flash('new_achievement', $achievement);
                    $achievementUnlocked = true;
                }
            }

            if ($achievementUnlocked) {
                $user->increment('exptotal', 100);
            }
        }

        if ($isLastLesson) {
            // Last lesson: Show course completion popup
            $xpEarned = $achievementUnlocked ? 100 : 0;
            $nextLessonUrl = route('courses.show', $course->name);  // Redirect to course page
            $nextBtnText = 'Explore More Courses';

            return redirect()->back()->with([
                'course_completed' => true,
                'xp_earned' => $xpEarned,
                'next_lesson_url' => $nextLessonUrl,
                'next_btn_text' => $nextBtnText,
            ]);
        } else {
            // Not last lesson: Show success popup
            $nextLesson = $allLessons->get($currentIndex + 1);
            $courseName = $course->name;
            $nextRedirectUrl = route('lessons.show', ['course' => $courseName, 'lesson' => $nextLesson->id]);

            return redirect()->back()->with([
                'fill_in_blank_success' => true,
                'next_redirect_url' => $nextRedirectUrl,
            ]);
        }
    }

    // For other activities, proceed as before
    if (!$alreadyCompleted) {
        $user->completedLessons()->attach($lesson->id, ['completed_at' => now()]);

        // Only award XP if achievement is unlocked
        if ($lesson->achievement_id) {
            if (!$user->achievements()->where('achievement_id', $lesson->achievement_id)->exists()) {
                $user->achievements()->attach($lesson->achievement_id);
                $achievement = Achievement::find($lesson->achievement_id);
                session()->flash('new_achievement', $achievement);
                $achievementUnlocked = true;
            }
        }

        if ($achievementUnlocked) {
            $user->increment('exptotal', 100);
        }
    }

    $course = $lesson->lessonGroup->course;
    $allLessons = $course->lessons()
        ->select('lessons.*')
        ->orderBy(\DB::raw('(SELECT `order` FROM lesson_groups WHERE lesson_groups.id = lessons.lesson_group_id)'))
        ->orderBy('lessons.order')
        ->get();
    
    $currentIndex = $allLessons->search(function($item) use ($lesson) {
        return $item->id === $lesson->id;
    });

    $nextLesson = null;
    if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
        $nextLesson = $allLessons->get($currentIndex + 1);
    }
    
    $courseName = $course->name;

    if ($nextLesson) {
        return redirect()->route('lessons.show', ['course' => $courseName, 'lesson' => $nextLesson->id])
                         ->with('success', 'Lesson completed!');
    } else {
        $xpEarned = $achievementUnlocked ? 100 : 0;
        $nextLessonUrl = route('courses.show', $course->name);  // Redirect to course page
        $nextBtnText = 'Explore More Courses';

        return redirect()->route('lessons.show', ['course' => $courseName, 'lesson' => $lesson->id])
                         ->with([
                             'course_completed' => true,
                             'xp_earned' => $xpEarned,
                             'next_lesson_url' => $nextLessonUrl,
                             'next_btn_text' => $nextBtnText,
                         ]);
    }
}
        public function runCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10000',
            'lang' => 'required|string',
        ]);

        $lang = $request->input('lang');
        $code = $request->input('code');

        // --- PRODUCTION CONFIGURATION ---
        // Determine OS and Docker Host automatically
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        // Use environment variable or defaults based on OS
        // Windows uses TCP, Linux uses Socket
        $dockerHost = env('DOCKER_HOST', $isWindows ? 'tcp://127.0.0.1:2375' : 'unix:///var/run/docker.sock');
        
        $baseCommand = ['docker', '-H', $dockerHost, 'run', '--rm', '-i'];

        $command = [];

        if ($lang === 'php') {
            $command = array_merge($baseCommand, ['php:8.2-cli', 'php']);
        } elseif ($lang === 'cpp') {
            $shellScript = 'cat > main.cpp && g++ main.cpp -o main && ./main';
            $command = array_merge($baseCommand, ['gcc:latest', 'sh', '-c', $shellScript]);
        } elseif ($lang === 'js') {
            $command = array_merge($baseCommand, ['node:18-alpine', 'node']);
        } else {
            return response()->json(['output' => '', 'error' => 'Unsupported language.'], 400);
        }

        try {
            $env = [
                'DOCKER_HOST' => $dockerHost,
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows', 
                'PATH' => getenv('PATH'), 
                'TEMP' => getenv('TEMP'), 
                'TMP' => getenv('TMP'),
            ];

            $process = new Process($command, null, $env);
            $process->setInput($code);
            $process->setTimeout(10);
            $process->run();

            if ($process->isSuccessful()) {
                return response()->json([
                    'output' => $process->getOutput(),
                    'error' => null,
                ]);
            } else {
                $errorOutput = $process->getErrorOutput();
                if (empty($errorOutput)) {
                    $errorOutput = $process->getOutput();
                }

                return response()->json([
                    'output' => null,
                    'error' => $errorOutput,
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'output' => null,
                'error' => 'System Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function checkDocker()
    {
        try {
            $dockerHost = 'tcp://127.0.0.1:2375';
            $env = [
                'DOCKER_HOST' => $dockerHost,
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
                'PATH' => getenv('PATH'),
            ];

            $process = new Process(['docker', '-H', $dockerHost, 'info'], null, $env);
            $process->setTimeout(2);
            $process->run();

            if ($process->isSuccessful()) {
                return response()->json(['connected' => true]);
            }

            return response()->json(['connected' => false]);

        } catch (\Exception $e) {
            return response()->json(['connected' => false, 'error' => $e->getMessage()]);
        }
    }
}