<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Workout;
use App\Models\CheckIn;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    public function index()
    {
        $student = auth()->user()->student;
        $student->load(['plan', 'instructor', 'workouts' => function ($query) {
            $query->latest('pivot_created_at')->take(5);
        }]);

        $recentCheckIns = $student->checkIns()
            ->latest()
            ->take(5)
            ->get();

        $nextPayment = $student->payments()
            ->where('due_date', '>', now())
            ->orderBy('due_date')
            ->first();

        return view('student.dashboard', compact('student', 'recentCheckIns', 'nextPayment'));
    }

    public function workouts()
    {
        $student = auth()->user()->student;
        $workouts = $student->workouts()
            ->withPivot('completed_at', 'notes')
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(10);

        return view('student.workouts', compact('workouts'));
    }

    public function completeWorkout(Request $request, Workout $workout)
    {
        $student = auth()->user()->student;
        
        // Validate that this workout is assigned to the student
        $assignment = $student->workouts()
            ->where('workout_id', $workout->id)
            ->wherePivot('completed_at', null)
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Workout not found or already completed.');
        }

        // Update the pivot to mark as completed
        $student->workouts()->updateExistingPivot($workout->id, [
            'completed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Workout marked as completed!');
    }

    public function progress()
    {
        $student = auth()->user()->student;
        
        // Get check-ins for the last 30 days
        $checkIns = $student->checkIns()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at')
            ->get();

        // Get completed workouts
        $completedWorkouts = $student->workouts()
            ->wherePivot('completed_at', '!=', null)
            ->withPivot('completed_at', 'notes')
            ->orderBy('pivot_completed_at', 'desc')
            ->get();

        return view('student.progress', compact('checkIns', 'completedWorkouts'));
    }

    public function payments()
    {
        $student = auth()->user()->student;
        $payments = $student->payments()
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('student.payments', compact('payments'));
    }

    public function checkIn(Request $request)
    {
        $student = auth()->user()->student;
        
        // Check if already checked in today
        $existingCheckIn = $student->checkIns()
            ->whereDate('created_at', today())
            ->first();

        if ($existingCheckIn) {
            return back()->with('error', 'You have already checked in today.');
        }

        $student->checkIns()->create([
            'created_at' => now(),
        ]);

        return back()->with('success', 'Check-in recorded successfully!');
    }

    public function checkInHistory()
    {
        $student = auth()->user()->student;
        $checkIns = $student->checkIns()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.check-ins', compact('checkIns'));
    }

    public function profile()
    {
        $student = auth()->user()->student;
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = auth()->user()->student;
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'health_conditions' => 'nullable|string',
        ]);

        $student->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
} 