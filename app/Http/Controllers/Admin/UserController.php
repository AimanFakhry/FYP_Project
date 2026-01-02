<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- Added Str import
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Generate the random 6-character token (Uppercase) for the new user
        $token = Str::upper(Str::random(6));

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
            'exptotal' => 0,
            'reset_token' => $token, // <-- Store the generated token
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user (Detailed History).
     */
    public function show(User $user)
    {
        // 1. Calculate Progress per Course
        $courses = Course::withCount('lessons')->get();
        $courseProgress = [];

        // Pre-load completed lessons to avoid N+1 queries in the loop
        // We attach 'lessonGroup' to filter by course
        $userCompletedLessons = $user->completedLessons()->with('lessonGroup')->get();

        foreach ($courses as $course) {
            $total = $course->lessons_count;
            
            // Filter the pre-loaded collection
            $completed = $userCompletedLessons->filter(function ($lesson) use ($course) {
                return $lesson->lessonGroup->course_id === $course->id;
            })->count();

            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

            $courseProgress[] = (object) [
                'name' => $course->name,
                'total' => $total,
                'completed' => $completed,
                'percentage' => $percentage,
                'color_class' => $course->color_class ?? 'bg-gray-500'
            ];
        }

        // 2. Fetch Activity History (Last 20 items)
        // We use withPivot to access the 'completed_at' timestamp
        $history = $user->completedLessons()
                        ->withPivot('completed_at')
                        ->with(['lessonGroup.course']) // Load context
                        ->orderByPivot('completed_at', 'desc')
                        ->take(20)
                        ->get();

        // 3. Achievements (Sorted by unlocked_at)
        $achievements = $user->achievements()
                             ->orderByPivot('unlocked_at', 'desc')
                             ->get();

        return view('admin.users.show', compact('user', 'courseProgress', 'history', 'achievements'));
    }

    /**
     * Show the form for editing.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}