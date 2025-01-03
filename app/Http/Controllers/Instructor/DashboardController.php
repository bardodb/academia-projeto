<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Workout;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get recent check-ins for students assigned to this instructor
        $recentCheckIns = CheckIn::with('student')
            ->whereHas('student', function ($query) {
                $query->where('instructor_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get active students assigned to this instructor
        $activeStudents = Student::with(['workouts' => function ($query) {
            $query->latest('pivot_created_at')->take(1);
        }, 'plan'])
        ->where('instructor_id', auth()->id())
        ->whereHas('checkIns', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })
        ->take(10)
        ->get();

        // Get total counts for this instructor
        $totalStudents = Student::where('instructor_id', auth()->id())->count();
        $totalWorkouts = Workout::where('created_by', auth()->id())->count();
        $todayCheckIns = CheckIn::whereHas('student', function ($query) {
            $query->where('instructor_id', auth()->id());
        })
        ->whereDate('created_at', today())
        ->count();

        return view('instructor.dashboard', compact(
            'recentCheckIns',
            'activeStudents',
            'totalStudents',
            'totalWorkouts',
            'todayCheckIns'
        ));
    }
}
