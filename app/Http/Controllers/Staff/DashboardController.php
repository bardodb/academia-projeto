<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Workout;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get recent check-ins
        $recentCheckIns = CheckIn::with('student')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get active students with their latest workouts
        $activeStudents = Student::with(['workouts' => function ($query) {
            $query->latest('pivot_created_at')->take(1);
        }, 'plan'])
        ->whereHas('checkIns', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })
        ->take(10)
        ->get();

        // Get total counts
        $totalStudents = Student::count();
        $totalWorkouts = Workout::count();
        $todayCheckIns = CheckIn::whereDate('created_at', today())->count();

        return view('staff.dashboard', compact(
            'recentCheckIns',
            'activeStudents',
            'totalStudents',
            'totalWorkouts',
            'todayCheckIns'
        ));
    }
}
