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
        // Remove redundant middleware since it's already in routes
    }

    protected function getAuthenticatedStudent()
    {
        return auth()->user()->student;
    }

    public function index()
    {
        try {
            $student = $this->getAuthenticatedStudent();
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
        } catch (\Exception $e) {
            report($e);
            return view('dashboard')->with('error', 'An error occurred while loading your dashboard. Please try again.');
        }
    }

    public function workouts()
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your workouts.');
        }

        try {
            $workouts = $student->workouts()
                ->withPivot('completed_at', 'notes')
                ->orderBy('pivot_created_at', 'desc')
                ->paginate(10);

            return view('student.workouts', compact('workouts', 'student'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to load workouts. Please try again.');
        }
    }

    public function completeWorkout(Request $request, Workout $workout)
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to complete workouts.');
        }

        try {
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
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to complete workout. Please try again.');
        }
    }

    public function progress()
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your progress.');
        }

        try {
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

            return view('student.progress', compact('checkIns', 'completedWorkouts', 'student'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to load progress data. Please try again.');
        }
    }

    public function payments()
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your payments.');
        }

        try {
            $payments = $student->payments()
                ->orderBy('due_date', 'desc')
                ->paginate(10);

            return view('student.payments', compact('payments', 'student'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to load payment history. Please try again.');
        }
    }

    public function checkIn(Request $request)
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to check in.');
        }

        try {
            // Check if already checked in today
            $existingCheckIn = $student->checkIns()
                ->whereDate('created_at', today())
                ->first();

            if ($existingCheckIn) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'You have already checked in today.');
            }

            // Generate QR code for this check-in
            $qrCode = 'CHK-' . time() . '-' . $student->id;

            // Create the check-in
            $checkIn = $student->checkIns()->create([
                'created_at' => now(),
                'qr_code' => $qrCode,
                'status' => 'checked_in'
            ]);

            // If student has Google Fit connected, sync their data
            if ($student->google_connected) {
                try {
                    app(\App\Http\Controllers\Auth\GoogleController::class)->syncFitnessData($student);
                    
                    // Update check-in with fitness data
                    $checkIn->update([
                        'steps' => $student->google_fit_data['steps'] ?? null,
                        'distance' => $student->google_fit_data['distance'] ?? null,
                        'calories' => $student->google_fit_data['calories'] ?? null,
                        'heart_rate_avg' => $student->google_fit_data['heart_rate']['average'] ?? null,
                        'weight' => $student->google_fit_data['weight'] ?? null,
                    ]);

                    return redirect()->route('student.dashboard')
                        ->with('success', 'Check-in recorded successfully with your fitness data!');
                } catch (\Exception $e) {
                    report($e);
                    return redirect()->route('student.dashboard')
                        ->with('success', 'Check-in recorded successfully, but unable to sync fitness data.');
                }
            }

            return redirect()->route('student.dashboard')
                ->with('success', 'Check-in recorded successfully!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to record check-in. Please try again.');
        }
    }

    public function checkInHistory()
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your check-in history.');
        }

        try {
            $checkIns = $student->checkIns()
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('student.check-ins', compact('checkIns', 'student'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to load check-in history. Please try again.');
        }
    }

    public function profile()
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your profile.');
        }

        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to update your profile.');
        }

        try {
            $validated = $request->validate([
                'phone' => 'nullable|string|max:20',
                'emergency_contact' => 'nullable|string|max:255',
                'emergency_phone' => 'nullable|string|max:20',
                'health_conditions' => 'nullable|string',
            ]);

            $student->update($validated);

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Unable to update profile. Please try again.');
        }
    }

    public function checkOut(Request $request)
    {
        $student = $this->getAuthenticatedStudent();

        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to check out.');
        }

        try {
            // Find today's check-in that hasn't been checked out
            $checkIn = $student->checkIns()
                ->whereDate('created_at', today())
                ->whereNull('check_out_time')
                ->first();

            if (!$checkIn) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'No active check-in found for today.');
            }

            // Update the check-in with check-out time and notes
            $checkIn->update([
                'check_out_time' => now(),
                'notes' => $request->input('notes'),
                'status' => 'completed'
            ]);

            return redirect()->route('student.dashboard')
                ->with('success', 'Check-out recorded successfully!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to record check-out. Please try again.');
        }
    }
} 